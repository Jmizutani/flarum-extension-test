import Modal from 'flarum/common/components/Modal';
import Button from 'flarum/common/components/Button';
import Switch from 'flarum/common/components/Switch';
import Stream from 'flarum/common/utils/Stream';

export default class UserProfileModal extends Modal {
  oninit(vnode) {
    super.oninit(vnode);
    
    const profile = this.attrs.profile || {};
    
    this.introduction = Stream(profile.introduction || '');
    this.childcareSituation = Stream(profile.childcareSituation || '');
    this.careSituation = Stream(profile.careSituation || '');
    this.facebookUrl = Stream(profile.facebookUrl || '');
    this.xUrl = Stream(profile.xUrl || '');
    this.instagramUrl = Stream(profile.instagramUrl || '');
    this.isVisible = Stream(profile.isVisible !== undefined ? profile.isVisible : true);
    this.loading = false;
  }

  className() {
    return 'UserProfileModal Modal--large';
  }

  title() {
    return 'プロフィール編集';
  }

  content() {
    return (
      <div className="Modal-body">
        <div className="Form">
          <div className="Form-group">
            <label>自己紹介</label>
            <textarea
              className="FormControl"
              value={this.introduction()}
              oninput={(e) => this.introduction(e.target.value)}
              rows="4"
              placeholder="自己紹介を入力してください"
            />
          </div>
          
          <div className="Form-group">
            <label>子育ての状況</label>
            <textarea
              className="FormControl"
              value={this.childcareSituation()}
              oninput={(e) => this.childcareSituation(e.target.value)}
              rows="3"
              placeholder="子育ての状況を入力してください"
            />
          </div>
          
          <div className="Form-group">
            <label>介護の状況</label>
            <textarea
              className="FormControl"
              value={this.careSituation()}
              oninput={(e) => this.careSituation(e.target.value)}
              rows="3"
              placeholder="介護の状況を入力してください"
            />
          </div>
          
          <div className="Form-group">
            <label>Facebook URL</label>
            <input
              className="FormControl"
              type="url"
              value={this.facebookUrl()}
              oninput={(e) => this.facebookUrl(e.target.value)}
              placeholder="https://facebook.com/username"
            />
          </div>
          
          <div className="Form-group">
            <label>X (Twitter) URL</label>
            <input
              className="FormControl"
              type="url"
              value={this.xUrl()}
              oninput={(e) => this.xUrl(e.target.value)}
              placeholder="https://x.com/username"
            />
          </div>
          
          <div className="Form-group">
            <label>Instagram URL</label>
            <input
              className="FormControl"
              type="url"
              value={this.instagramUrl()}
              oninput={(e) => this.instagramUrl(e.target.value)}
              placeholder="https://instagram.com/username"
            />
          </div>
          
          <div className="Form-group">
            <Switch
              state={this.isVisible()}
              onchange={this.isVisible}
            >
              プロフィールを他のユーザーに公開する
            </Switch>
          </div>
          
          <div className="Form-group">
            <Button
              className="Button Button--primary"
              type="submit"
              loading={this.loading}
            >
              保存
            </Button>
            <Button
              className="Button Button--default"
              onclick={this.hide.bind(this)}
            >
              キャンセル
            </Button>
          </div>
        </div>
      </div>
    );
  }

  onsubmit(e) {
    e.preventDefault();
    
    this.loading = true;
    m.redraw();
    
    const data = {
      userId: app.session.user.id(),
      introduction: this.introduction(),
      childcareSituation: this.childcareSituation(),
      careSituation: this.careSituation(),
      facebookUrl: this.facebookUrl(),
      xUrl: this.xUrl(),
      instagramUrl: this.instagramUrl(),
      isVisible: this.isVisible()
    };
    
    app.store.createRecord('user-profiles')
      .save(data)
      .then(() => {
        this.hide();
        m.redraw();
      })
      .catch(() => {
        this.loading = false;
        m.redraw();
      });
  }
}