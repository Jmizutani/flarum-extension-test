import Modal from 'flarum/common/components/Modal';
import Button from 'flarum/common/components/Button';
import Switch from 'flarum/common/components/Switch';
import Stream from 'flarum/common/utils/Stream';

export default class UserProfileModal extends Modal {
  oninit(vnode) {
    super.oninit(vnode);
    
    const profile = this.attrs.profile || {};
    
    // プロフィールが関数の場合は実行して値を取得
    const facebookUrl = typeof profile.facebookUrl === 'function' ? profile.facebookUrl() : profile.facebookUrl;
    const xUrl = typeof profile.xUrl === 'function' ? profile.xUrl() : profile.xUrl;
    const instagramUrl = typeof profile.instagramUrl === 'function' ? profile.instagramUrl() : profile.instagramUrl;
    const isVisible = typeof profile.isVisible === 'function' ? profile.isVisible() : profile.isVisible;
    const customFields = typeof profile.customFields === 'function' ? profile.customFields() : profile.customFields;
    
    this.facebookUrl = Stream(facebookUrl || '');
    this.xUrl = Stream(xUrl || '');
    this.instagramUrl = Stream(instagramUrl || '');
    this.isVisible = Stream(isVisible !== undefined ? isVisible : true);
    this.loading = false;
    this.fieldsLoading = true;
    this.fields = [];
    this.customFields = {};
    
    // カスタムフィールドの初期化
    if (customFields) {
      this.customFields = { ...customFields };
    }
    
    this.loadFields();
  }

  loadFields() {
    app.store.find('profile-fields')
      .then(fields => {
        this.fields = fields.sort((a, b) => a.sortOrder() - b.sortOrder());
        
        // カスタムフィールドのStreamを初期化
        this.fields.forEach(field => {
          if (!this.customFields[field.name()]) {
            this.customFields[field.name()] = Stream('');
          } else {
            this.customFields[field.name()] = Stream(this.customFields[field.name()]);
          }
        });
        
        this.fieldsLoading = false;
        m.redraw();
      })
      .catch(() => {
        this.fieldsLoading = false;
        m.redraw();
      });
  }

  className() {
    return 'UserProfileModal Modal--large';
  }

  title() {
    return 'プロフィール編集';
  }

  content() {
    if (this.fieldsLoading) {
      return (
        <div className="Modal-body">
          <div className="LoadingIndicator"></div>
        </div>
      );
    }

    return (
      <div className="Modal-body">
        <div className="Form">
          {/* カスタムフィールド */}
          {this.fields.map(field => this.renderCustomField(field))}
          
          {/* ソーシャルリンク */}
          <div className="Form-group">
            <h4>ソーシャルリンク</h4>
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

  renderCustomField(field) {
    const stream = this.customFields[field.name()];
    
    if (!stream) {
      return null;
    }

    return (
      <div key={field.id()} className="Form-group">
        <label>
          {field.label()}
          {field.required() && <span className="required">*</span>}
        </label>
        {field.type() === 'textarea' ? (
          <textarea
            className="FormControl"
            value={stream()}
            oninput={(e) => stream(e.target.value)}
            rows="4"
            placeholder={`${field.label()}を入力してください`}
            required={field.required()}
          />
        ) : (
          <input
            className="FormControl"
            type="text"
            value={stream()}
            oninput={(e) => stream(e.target.value)}
            placeholder={`${field.label()}を入力してください`}
            required={field.required()}
          />
        )}
      </div>
    );
  }

  onsubmit(e) {
    e.preventDefault();
    
    // 必須フィールドのチェック
    for (const field of this.fields) {
      if (field.required() && field.isActive()) {
        const stream = this.customFields[field.name()];
        if (!stream || !stream().trim()) {
          alert(`${field.label()}は必須項目です。`);
          return;
        }
      }
    }
    
    this.loading = true;
    m.redraw();
    
    const customFieldsData = {};
    for (const field of this.fields) {
      const stream = this.customFields[field.name()];
      if (stream) {
        customFieldsData[field.name()] = stream();
      }
    }
    
    const data = {
      userId: app.session.user.id(),
      facebookUrl: this.facebookUrl(),
      xUrl: this.xUrl(),
      instagramUrl: this.instagramUrl(),
      isVisible: this.isVisible(),
      customFields: customFieldsData
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