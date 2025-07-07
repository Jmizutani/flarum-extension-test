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
    this.socialLinksLoading = true;
    this.fields = [];
    this.socialLinks = [];
    this.customFields = {};
    
    // カスタムフィールドの初期化
    if (customFields) {
      this.customFields = { ...customFields };
    }
    
    this.loadFields();
    this.loadSocialLinks();
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

  loadSocialLinks() {
    app.store.find('social-links')
      .then(links => {
        this.socialLinks = links
          .filter(link => link.isActive())
          .sort((a, b) => a.sortOrder() - b.sortOrder());
        this.socialLinksLoading = false;
        m.redraw();
      })
      .catch(error => {
        console.error('ソーシャルリンクの読み込みに失敗しました:', error);
        // エラーの場合はデフォルトのソーシャルリンクを使用
        this.socialLinks = this.getDefaultSocialLinks();
        this.socialLinksLoading = false;
        m.redraw();
      });
  }

  getDefaultSocialLinks() {
    return [
      {
        name: () => 'facebook',
        label: () => 'Facebook URL',
        placeholder: () => 'https://facebook.com/username'
      },
      {
        name: () => 'x',
        label: () => 'X (Twitter) URL',
        placeholder: () => 'https://x.com/username'
      },
      {
        name: () => 'instagram',
        label: () => 'Instagram URL',
        placeholder: () => 'https://instagram.com/username'
      }
    ];
  }

  className() {
    return 'UserProfileModal Modal--large';
  }

  title() {
    return 'プロフィール編集';
  }

  oncreate(vnode) {
    super.oncreate(vnode);
    
    // モーダルが開いたら最初のフォーカス可能な要素にフォーカスを移動
    setTimeout(() => {
      this.focusFirstElement(vnode.dom);
    }, 100);
    
    // フォーカストラップを設定
    this.trapFocus(vnode.dom);
  }

  focusFirstElement(container) {
    const focusableElements = container.querySelectorAll(
      'input:not([disabled]), textarea:not([disabled]), select:not([disabled]), button:not([disabled]), [tabindex]:not([tabindex="-1"])'
    );
    
    if (focusableElements.length > 0) {
      focusableElements[0].focus();
    }
  }

  trapFocus(container) {
    const focusableElements = container.querySelectorAll(
      'input:not([disabled]), textarea:not([disabled]), select:not([disabled]), button:not([disabled]), [tabindex]:not([tabindex="-1"])'
    );
    
    const firstElement = focusableElements[0];
    const lastElement = focusableElements[focusableElements.length - 1];

    container.addEventListener('keydown', (e) => {
      if (e.key === 'Tab') {
        if (e.shiftKey) {
          if (document.activeElement === firstElement) {
            e.preventDefault();
            lastElement.focus();
          }
        } else {
          if (document.activeElement === lastElement) {
            e.preventDefault();
            firstElement.focus();
          }
        }
      }
    });
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
          
          {this.socialLinksLoading ? (
            <div className="LoadingIndicator">読み込み中...</div>
          ) : (
            this.socialLinks.map(link => {
              const fieldName = link.name();
              let fieldStream;
              
              // 既存の固定フィールドとのマッピング
              if (fieldName === 'facebook') {
                fieldStream = this.facebookUrl;
              } else if (fieldName === 'x') {
                fieldStream = this.xUrl;
              } else if (fieldName === 'instagram') {
                fieldStream = this.instagramUrl;
              } else {
                // 新しい動的フィールドの場合
                if (!this[fieldName + 'Url']) {
                  this[fieldName + 'Url'] = Stream('');
                }
                fieldStream = this[fieldName + 'Url'];
              }
              
              return (
                <div key={fieldName} className="Form-group">
                  <label>{link.label()}</label>
                  <input
                    className="FormControl"
                    type="url"
                    value={fieldStream()}
                    oninput={(e) => fieldStream(e.target.value)}
                    placeholder={link.placeholder()}
                  />
                </div>
              );
            })
          )}
          
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
              tabindex="0"
            >
              保存
            </Button>
            <Button
              className="Button Button--default"
              onclick={this.hide.bind(this)}
              tabindex="0"
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
      .then((savedProfile) => {
        // プロフィールをストアに保存
        app.store.pushObject(savedProfile);
        
        // コールバックがある場合は実行
        if (this.attrs.onSave) {
          this.attrs.onSave(savedProfile);
        }
        
        this.hide();
        m.redraw();
      })
      .catch(() => {
        this.loading = false;
        m.redraw();
      });
  }
}