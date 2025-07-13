import Component from 'flarum/common/Component';
import Button from 'flarum/common/components/Button';
import UserProfileModal from './UserProfileModal';

export default class UserProfileWidget extends Component {
  oninit(vnode) {
    super.oninit(vnode);
    this.user = this.attrs.user;
    this.profile = null;
    this.fields = [];
    this.socialLinks = [];
    this.loading = true;
    this.fieldsLoading = true;
    this.socialLinksLoading = true;
    
    this.loadProfile();
    this.loadFields();
    this.loadSocialLinks();
  }

  loadProfile() {
    app.store.find('user-profiles', { userId: this.user.id() })
      .then(profile => {
        this.profile = profile;
        this.loading = false;
        m.redraw();
      })
      .catch(() => {
        this.loading = false;
        m.redraw();
      });
  }

  loadFields() {
    app.store.find('profile-fields')
      .then(fields => {
        this.fields = fields.sort((a, b) => a.sortOrder() - b.sortOrder());
        this.fieldsLoading = false;
        m.redraw();
      })
      .catch(() => {
        this.fieldsLoading = false;
        m.redraw();
      });
  }

  loadSocialLinks() {
    app.store.find('social-links')
      .then(socialLinks => {
        this.socialLinks = socialLinks.sort((a, b) => a.sortOrder() - b.sortOrder());
        this.socialLinksLoading = false;
        m.redraw();
      })
      .catch(() => {
        this.socialLinksLoading = false;
        m.redraw();
      });
  }

  refreshProfile(newProfile) {
    this.profile = newProfile;
    m.redraw();
  }

  view() {
    if (this.loading || this.fieldsLoading || this.socialLinksLoading) {
      return <div className="UserProfileWidget">読み込み中...</div>;
    }

    const isOwnProfile = app.session.user && app.session.user.id() === this.user.id();
    const canView = this.profile && (this.profile.isVisible() || isOwnProfile || (app.session.user && app.session.user.isAdmin()));

    return (
      <div className="UserProfileWidget">
        <div className="UserProfileWidget-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; flex-wrap: nowrap;">
          <h3 style="margin: 0; flex-shrink: 0; white-space: nowrap;">プロフィール</h3>
          {isOwnProfile && canView && this.profile && (
            <Button
              className="Button Button--primary Button--small"
              onclick={() => app.modal.show(UserProfileModal, { 
                profile: this.profile,
                onSave: (newProfile) => this.refreshProfile(newProfile)
              })}
              style="margin-left: 10px; flex-shrink: 0;"
            >
              編集
            </Button>
          )}
        </div>
        
        {canView && this.profile ? (
          <div className="UserProfileWidget-content">
            {/* カスタムフィールドの表示 */}
            {this.renderCustomFields()}
            
            {/* ソーシャルリンク */}
            {this.renderSocialLinks()}
            
            {!this.profile.isVisible() && isOwnProfile && (
              <div className="UserProfileWidget-section">
                <em>このプロフィールは非公開に設定されています。</em>
              </div>
            )}
          </div>
        ) : (
          <div className="UserProfileWidget-content">
            {isOwnProfile ? (
              <div className="UserProfileWidget-empty" style="text-align: center; padding: 20px; background: #f8f9fa; border-radius: 6px; border: 1px solid #e9ecef;">
                <p style="margin-bottom: 15px; color: #666;">プロフィールが設定されていません。</p>
                <Button
                  className="Button Button--primary"
                  onclick={() => app.modal.show(UserProfileModal, { 
                    profile: null,
                    onSave: (newProfile) => this.refreshProfile(newProfile)
                  })}
                  style="width: 100%; max-width: 200px;"
                >
                  プロフィールを作成
                </Button>
              </div>
            ) : (
              <div className="UserProfileWidget-empty" style="text-align: center; padding: 20px; background: #f8f9fa; border-radius: 6px; border: 1px solid #e9ecef;">
                <p style="margin: 0; color: #666;">プロフィールは非公開に設定されています。</p>
              </div>
            )}
          </div>
        )}
      </div>
    );
  }

  renderCustomFields() {
    if (!this.profile || !this.profile.customFields) {
      return null;
    }

    const customFields = this.profile.customFields();
    
    return this.fields
      .filter(field => field.isActive() && customFields && customFields[field.name()])
      .map(field => (
        <div key={field.id()} className="UserProfileWidget-section">
          <h4>{field.label()}</h4>
          <p>{customFields[field.name()]}</p>
        </div>
      ));
  }

  renderSocialLinks() {
    if (!this.profile) {
      return null;
    }

    const socialLinkValues = {
      facebook: this.profile.facebookUrl(),
      x: this.profile.xUrl(),
      instagram: this.profile.instagramUrl()
    };

    const hasAnyLink = Object.values(socialLinkValues).some(value => value);
    
    if (!hasAnyLink) {
      return null;
    }

    return (
      <div className="UserProfileWidget-section">
        <h4>ソーシャルリンク</h4>
        <div className="UserProfileWidget-socialLinks">
          {this.socialLinks
            .filter(socialLink => {
              const value = socialLinkValues[socialLink.name()];
              return value && socialLink.isActive();
            })
            .map(socialLink => {
              const value = socialLinkValues[socialLink.name()];
              return (
                <a 
                  key={socialLink.id()}
                  href={value} 
                  target="_blank" 
                  rel="noopener noreferrer"
                  className="UserProfileWidget-socialLink"
                  title={socialLink.label()}
                >
                  <img 
                    src={socialLink.iconUrl()} 
                    alt={socialLink.label()} 
                    className="UserProfileWidget-socialIcon"
                  />
                </a>
              );
            })}
        </div>
      </div>
    );
  }
}