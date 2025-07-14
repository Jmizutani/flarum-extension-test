import Modal from 'flarum/common/components/Modal';
import Button from 'flarum/common/components/Button';
import Stream from 'flarum/common/utils/Stream';

export default class SocialLinkModal extends Modal {
  oninit(vnode) {
    super.oninit(vnode);

    this.socialLink = this.attrs.socialLink;
    
    this.name = Stream(this.socialLink?.name() || '');
    this.label = Stream(this.socialLink?.label() || '');
    this.iconUrl = Stream(this.socialLink?.iconUrl() || '');
    this.placeholder = Stream(this.socialLink?.placeholder() || '');
    this.sortOrder = Stream(this.socialLink?.sortOrder() || 0);
    this.isActive = Stream(this.socialLink?.isActive() ?? true);
    
    this.errors = {};
  }

  className() {
    return 'SocialLinkModal Modal--medium';
  }

  title() {
    return this.socialLink ? 'ソーシャルリンクを編集' : '新しいソーシャルリンクを追加';
  }

  content() {
    return (
      <div className="Modal-body">
        <div className="Form">
          <div className="Form-group">
            <label>ソーシャルリンク名 (内部用) <span className="required">*</span></label>
            <input
              className={`FormControl ${this.errors.name ? 'FormControl--error' : ''}`}
              type="text"
              placeholder="facebook, x, instagram など"
              bidi={this.name}
              required
            />
            {this.errors.name && (
              <div className="Form-error">
                {this.errors.name}
              </div>
            )}
            <div className="helpText">
              システム内部で使用される識別子です。英数字とアンダースコアのみ使用可能。
            </div>
          </div>

          <div className="Form-group">
            <label>表示名 <span className="required">*</span></label>
            <input
              className={`FormControl ${this.errors.label ? 'FormControl--error' : ''}`}
              type="text"
              placeholder="Facebook, X (Twitter), Instagram など"
              bidi={this.label}
              required
            />
            {this.errors.label && (
              <div className="Form-error">
                {this.errors.label}
              </div>
            )}
          </div>

          <div className="Form-group">
            <label>アイコンURL <span className="required">*</span></label>
            <input
              className={`FormControl ${this.errors.iconUrl ? 'FormControl--error' : ''}`}
              type="url"
              placeholder="https://example.com/icon.svg"
              bidi={this.iconUrl}
              required
            />
            {this.errors.iconUrl && (
              <div className="Form-error">
                {this.errors.iconUrl}
              </div>
            )}
            <div className="helpText">
              アイコンのURLを入力してください。PNG、SVG、JPGなどの画像形式に対応しています。
            </div>
          </div>

          <div className="Form-group">
            <label>プレースホルダー</label>
            <input
              className="FormControl"
              type="text"
              placeholder="https://facebook.com/username"
              bidi={this.placeholder}
            />
            <div className="helpText">
              ユーザーの入力欄に表示されるプレースホルダー（入力例）を設定します。
            </div>
          </div>

          <div className="Form-group">
            <label>表示順序</label>
            <input
              className="FormControl"
              type="number"
              min="0"
              bidi={this.sortOrder}
            />
          </div>

          <div className="Form-group">
            <label>有効状態</label>
            <div style="display: flex; align-items: center; margin-top: 5px;">
              <div 
                className={`switch ${this.isActive() ? 'switch--on' : 'switch--off'}`}
                onclick={() => this.isActive(!this.isActive())}
                style={`position: relative; width: 50px; height: 24px; background: ${this.isActive() ? '#4CAF50' : '#ccc'}; border-radius: 12px; cursor: pointer; transition: all 0.2s ease;`}
              >
                <div 
                  className="switch-thumb"
                  style={`position: absolute; top: 2px; left: ${this.isActive() ? '26px' : '2px'}; width: 20px; height: 20px; background: white; border-radius: 50%; transition: all 0.2s ease; box-shadow: 0 1px 3px rgba(0,0,0,0.3);`}
                ></div>
              </div>
              <span style="margin-left: 10px; color: #666;">
                {this.isActive() ? '有効' : '無効'}
              </span>
            </div>
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

    // バリデーション
    this.errors = {};
    
    if (!this.name() || this.name().trim() === '') {
      this.errors.name = '名前は必須です。';
    }
    
    if (!this.label() || this.label().trim() === '') {
      this.errors.label = '表示名は必須です。';
    }
    
    if (!this.iconUrl() || this.iconUrl().trim() === '') {
      this.errors.iconUrl = 'アイコンURLは必須です。';
    } else {
      // URL形式のチェック
      try {
        new URL(this.iconUrl());
      } catch {
        this.errors.iconUrl = '有効なURLを入力してください。';
      }
    }
    
    // エラーがある場合は送信を停止
    if (Object.keys(this.errors).length > 0) {
      m.redraw();
      return;
    }

    this.loading = true;

    const data = {
      name: this.name(),
      label: this.label(),
      iconUrl: this.iconUrl(),
      placeholder: this.placeholder(),
      sortOrder: parseInt(this.sortOrder()) || 0,
      isActive: this.isActive()
    };

    let promise;
    
    if (this.socialLink) {
      // 既存ソーシャルリンクの更新 - 直接APIコールを使用
      promise = app.request({
        url: app.forum.attribute('apiUrl') + '/social-links/' + this.socialLink.id(),
        method: 'PATCH',
        body: {
          data: {
            type: 'social-links',
            id: this.socialLink.id(),
            attributes: data
          }
        }
      }).then(response => {
        app.store.pushPayload(response);
        return app.store.getById('social-links', this.socialLink.id());
      });
    } else {
      // 新規ソーシャルリンクの作成
      promise = app.store.createRecord('social-links').save(data);
    }

    promise
      .then(() => {
        this.hide();
        if (this.attrs.onsubmit) {
          this.attrs.onsubmit();
        }
      })
      .catch(error => {
        console.error('Social link save error:', error);
        
        // サーバーサイドエラーを表示
        if (error.response && error.response.errors) {
          error.response.errors.forEach(err => {
            if (err.source && err.source.pointer === '/data/attributes/icon_url') {
              this.errors.iconUrl = err.detail || 'アイコンURLでエラーが発生しました。';
            }
          });
        }
      })
      .then(() => {
        this.loading = false;
        m.redraw();
      });
  }
}