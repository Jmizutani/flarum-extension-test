import Component from 'flarum/common/Component';
import Button from 'flarum/common/components/Button';
import UserProfileModal from './UserProfileModal';

export default class UserProfileWidget extends Component {
  oninit(vnode) {
    super.oninit(vnode);
    this.user = this.attrs.user;
    this.profile = null;
    this.fields = [];
    this.loading = true;
    this.fieldsLoading = true;
    
    this.loadProfile();
    this.loadFields();
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

  view() {
    if (this.loading || this.fieldsLoading) {
      return <div className="UserProfileWidget">読み込み中...</div>;
    }

    const isOwnProfile = app.session.user && app.session.user.id() === this.user.id();
    const canView = this.profile && (this.profile.isVisible() || isOwnProfile || (app.session.user && app.session.user.isAdmin()));

    return (
      <div className="UserProfileWidget">
        <div className="UserProfileWidget-header">
          <h3>プロフィール</h3>
          {isOwnProfile && (
            <Button
              className="Button Button--primary Button--small"
              onclick={() => app.modal.show(UserProfileModal, { profile: this.profile })}
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
            {(this.profile.facebookUrl() || this.profile.xUrl() || this.profile.instagramUrl()) && (
              <div className="UserProfileWidget-section">
                <h4>ソーシャルリンク</h4>
                <div className="UserProfileWidget-socialLinks">
                  {this.profile.facebookUrl() && (
                    <a href={this.profile.facebookUrl()} target="_blank" rel="noopener noreferrer">
                      Facebook
                    </a>
                  )}
                  {this.profile.xUrl() && (
                    <a href={this.profile.xUrl()} target="_blank" rel="noopener noreferrer">
                      X (Twitter)
                    </a>
                  )}
                  {this.profile.instagramUrl() && (
                    <a href={this.profile.instagramUrl()} target="_blank" rel="noopener noreferrer">
                      Instagram
                    </a>
                  )}
                </div>
              </div>
            )}
            
            {!this.profile.isVisible() && isOwnProfile && (
              <div className="UserProfileWidget-section">
                <em>このプロフィールは非公開に設定されています。</em>
              </div>
            )}
          </div>
        ) : (
          <div className="UserProfileWidget-content">
            {isOwnProfile ? (
              <div className="UserProfileWidget-empty">
                <p>プロフィールが設定されていません。</p>
                <Button
                  className="Button Button--primary"
                  onclick={() => app.modal.show(UserProfileModal, { profile: null })}
                >
                  プロフィールを作成
                </Button>
              </div>
            ) : (
              <div className="UserProfileWidget-empty">
                <p>プロフィールは非公開に設定されています。</p>
              </div>
            )}
          </div>
        )}
      </div>
    );
  }

  renderCustomFields() {
    if (!this.profile.customFields) {
      return null;
    }

    const customFields = this.profile.customFields();
    const sections = [];

    this.fields.forEach(field => {
      if (field.isActive() && customFields[field.name()]) {
        sections.push(
          <div key={field.id()} className="UserProfileWidget-section">
            <h4>{field.label()}</h4>
            <p>{customFields[field.name()]}</p>
          </div>
        );
      }
    });

    return sections;
  }
}