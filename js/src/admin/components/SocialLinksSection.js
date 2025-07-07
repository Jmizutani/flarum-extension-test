import Component from 'flarum/common/Component';
import Button from 'flarum/common/components/Button';
import LoadingIndicator from 'flarum/common/components/LoadingIndicator';
import SocialLinkModal from './SocialLinkModal';

export default class SocialLinksSection extends Component {
  oninit(vnode) {
    super.oninit(vnode);
    
    this.loading = true;
    this.socialLinks = [];
    
    this.loadSocialLinks();
  }

  loadSocialLinks() {
    this.loading = true;
    
    app.store.find('social-links')
      .then(socialLinks => {
        this.socialLinks = socialLinks.sort((a, b) => a.sortOrder() - b.sortOrder());
        this.loading = false;
        m.redraw();
      })
      .catch(() => {
        this.loading = false;
        m.redraw();
      });
  }

  view() {
    if (this.loading) {
      return (
        <div className="Form-group">
          <LoadingIndicator />
        </div>
      );
    }

    return (
      <div className="Form-group">
        <label>ソーシャルリンク設定</label>
        <div className="helpText" style="margin-bottom: 15px;">
          ユーザーが入力可能なソーシャルリンクを管理できます。
        </div>
        
        <div style="margin-bottom: 15px;">
          <Button
            className="Button Button--primary"
            onclick={() => this.showModal()}
          >
            新しいソーシャルリンクを追加
          </Button>
        </div>
        
        {this.socialLinks.length === 0 ? (
          <div className="helpText">
            ソーシャルリンクが設定されていません。
          </div>
        ) : (
          <div>
            {this.socialLinks.map(socialLink => this.socialLinkItem(socialLink))}
          </div>
        )}
      </div>
    );
  }

  socialLinkItem(socialLink) {
    return (
      <div 
        key={socialLink.id()} 
        className="ExtensionListItem"
        style="display: flex; justify-content: space-between; align-items: center; padding: 10px; margin-bottom: 5px; border: 1px solid #ddd; border-radius: 4px;"
      >
        <div style="display: flex; align-items: center;">
          {socialLink.iconUrl() && (
            <img 
              src={socialLink.iconUrl()} 
              alt={socialLink.label()} 
              style="width: 20px; height: 20px; margin-right: 10px;"
            />
          )}
          <div>
            <div style="font-weight: bold; margin-bottom: 5px;">
              {socialLink.label()}
              {!socialLink.isActive() && <span style="color: #888; margin-left: 5px;">(無効)</span>}
            </div>
            <div style="font-size: 12px; color: #666;">
              <span>名前: {socialLink.name()}</span>
              {socialLink.placeholder() && (
                <span style="margin-left: 15px;">プレースホルダー: {socialLink.placeholder()}</span>
              )}
            </div>
          </div>
        </div>
        
        <div>
          <Button
            className="Button Button--link"
            onclick={() => this.showModal(socialLink)}
            style="margin-right: 10px;"
          >
            編集
          </Button>
          <Button
            className="Button Button--link"
            onclick={() => this.deleteSocialLink(socialLink)}
          >
            削除
          </Button>
        </div>
      </div>
    );
  }

  showModal(socialLink = null) {
    app.modal.show(SocialLinkModal, {
      socialLink: socialLink,
      onsubmit: () => this.loadSocialLinks()
    });
  }

  deleteSocialLink(socialLink) {
    if (confirm(`ソーシャルリンク「${socialLink.label()}」を削除してもよろしいですか？`)) {
      m.request({
        method: 'POST',
        url: app.forum.attribute('apiUrl') + '/social-links/' + socialLink.id() + '/delete',
        headers: {
          'X-CSRF-Token': app.session.csrfToken,
          'X-HTTP-Method-Override': 'DELETE'
        }
      })
        .then(() => this.loadSocialLinks())
        .catch(error => {
          console.error('Delete error:', error);
        });
    }
  }
}