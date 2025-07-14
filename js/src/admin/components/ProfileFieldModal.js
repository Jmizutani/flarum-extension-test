import Modal from 'flarum/common/components/Modal';
import Button from 'flarum/common/components/Button';
import Stream from 'flarum/common/utils/Stream';

export default class ProfileFieldModal extends Modal {
  oninit(vnode) {
    super.oninit(vnode);
    
    const field = this.attrs.field;
    
    this.name = Stream(field ? field.name() : '');
    this.label = Stream(field ? field.label() : '');
    this.type = Stream(field ? field.type() : 'text');
    this.placeholder = Stream(field ? field.placeholder() : '');
    this.required = Stream(field ? field.required() : false);
    this.sortOrder = Stream(field ? field.sortOrder() : 0);
    this.isActive = Stream(field ? field.isActive() : true);
    this.loading = false;
    this.errors = {};
  }

  className() {
    return 'ProfileFieldModal Modal--medium';
  }

  title() {
    return this.attrs.field ? 'フィールドを編集' : '新しいフィールドを追加';
  }

  content() {
    return (
      <div className="Modal-body">
        <div className="Form">
          <div className="Form-group">
            <label>フィールド名 (内部用) <span className="required">*</span></label>
            <input
              className={`FormControl ${this.errors.name ? 'FormControl--error' : ''}`}
              type="text"
              value={this.name()}
              oninput={(e) => this.name(e.target.value)}
              placeholder="例: introduction"
              disabled={!!this.attrs.field}
              required
            />
            {this.errors.name && (
              <div className="Form-error">
                {this.errors.name}
              </div>
            )}
            {this.attrs.field && (
              <p className="helpText">既存フィールドの内部名は変更できません。</p>
            ) || (
              <div className="helpText">
                システム内部で使用される識別子です。英数字とアンダースコアのみ使用可能。
              </div>
            )}
          </div>
          
          <div className="Form-group">
            <label>表示ラベル <span className="required">*</span></label>
            <input
              className={`FormControl ${this.errors.label ? 'FormControl--error' : ''}`}
              type="text"
              value={this.label()}
              oninput={(e) => this.label(e.target.value)}
              placeholder="例: 自己紹介"
              required
            />
            {this.errors.label && (
              <div className="Form-error">
                {this.errors.label}
              </div>
            )}
          </div>
          
          <div className="Form-group">
            <label>フィールドタイプ</label>
            <select
              className="FormControl"
              value={this.type()}
              onchange={(e) => this.type(e.target.value)}
            >
              <option value="text">テキスト（1行）</option>
              <option value="textarea">テキストエリア（複数行）</option>
            </select>
          </div>
          
          <div className="Form-group">
            <label>プレースホルダー</label>
            <input
              className="FormControl"
              type="text"
              value={this.placeholder()}
              oninput={(e) => this.placeholder(e.target.value)}
              placeholder="ユーザーの入力欄に表示される例文"
            />
            <div className="helpText">
              ユーザーがフィールドを入力する際のヒントとして表示されます。
            </div>
          </div>
          
          <div className="Form-group">
            <label>並び順</label>
            <input
              className="FormControl"
              type="number"
              value={this.sortOrder()}
              oninput={(e) => this.sortOrder(parseInt(e.target.value) || 0)}
              min="0"
            />
          </div>
          
          <div className="Form-group">
            <label>必須フィールド</label>
            <div style="display: flex; align-items: center; margin-top: 5px;">
              <div 
                className={`switch ${this.required() ? 'switch--on' : 'switch--off'}`}
                onclick={() => this.required(!this.required())}
                style={`position: relative; width: 50px; height: 24px; background: ${this.required() ? '#4CAF50' : '#ccc'}; border-radius: 12px; cursor: pointer; transition: all 0.2s ease;`}
              >
                <div 
                  className="switch-thumb"
                  style={`position: absolute; top: 2px; left: ${this.required() ? '26px' : '2px'}; width: 20px; height: 20px; background: white; border-radius: 50%; transition: all 0.2s ease; box-shadow: 0 1px 3px rgba(0,0,0,0.3);`}
                ></div>
              </div>
              <span style="margin-left: 10px; color: #666;">
                {this.required() ? '必須' : '任意'}
              </span>
            </div>
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
      this.errors.name = 'フィールド名は必須です。';
    }
    
    if (!this.label() || this.label().trim() === '') {
      this.errors.label = '表示ラベルは必須です。';
    }
    
    // エラーがある場合は送信を停止
    if (Object.keys(this.errors).length > 0) {
      m.redraw();
      return;
    }
    
    this.loading = true;
    m.redraw();
    
    const data = {
      name: this.name(),
      label: this.label(),
      type: this.type(),
      placeholder: this.placeholder(),
      required: this.required(),
      sortOrder: this.sortOrder(),
      isActive: this.isActive()
    };
    
    let request;
    
    if (this.attrs.field) {
      // 既存フィールドの更新 - 直接APIコールを使用
      const requestConfig = {
        url: app.forum.attribute('apiUrl') + '/profile-fields/' + this.attrs.field.id(),
        method: 'PATCH',
        body: {
          data: {
            type: 'profile-fields',
            id: this.attrs.field.id(),
            attributes: data
          }
        }
      };
      
      request = app.request(requestConfig).then(response => {
        app.store.pushPayload(response);
        return app.store.getById('profile-fields', this.attrs.field.id());
      }).catch(error => {
        throw error;
      });
    } else {
      // 新規フィールドの作成
      request = app.store.createRecord('profile-fields').save(data);
    }
    
    request
      .then(() => {
        this.hide();
        if (this.attrs.onsubmit) {
          this.attrs.onsubmit();
        }
      })
      .catch(() => {
        this.loading = false;
        m.redraw();
      });
  }
}