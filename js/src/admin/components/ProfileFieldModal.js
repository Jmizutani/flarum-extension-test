import Modal from 'flarum/common/components/Modal';
import Button from 'flarum/common/components/Button';
import Switch from 'flarum/common/components/Switch';
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
            <label>フィールド名 (内部名)</label>
            <input
              className="FormControl"
              type="text"
              value={this.name()}
              oninput={(e) => this.name(e.target.value)}
              placeholder="例: introduction"
              disabled={!!this.attrs.field}
            />
            {this.attrs.field && (
              <p className="helpText">既存フィールドの内部名は変更できません。</p>
            )}
          </div>
          
          <div className="Form-group">
            <label>表示ラベル</label>
            <input
              className="FormControl"
              type="text"
              value={this.label()}
              oninput={(e) => this.label(e.target.value)}
              placeholder="例: 自己紹介"
            />
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
            <Switch
              state={this.required()}
              onchange={this.required}
            >
              必須フィールド
            </Switch>
          </div>
          
          <div className="Form-group">
            <Switch
              state={this.isActive()}
              onchange={this.isActive}
            >
              有効
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
    
    if (!this.name() || !this.label()) {
      alert('フィールド名と表示ラベルは必須です。');
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
      request = app.request({
        url: app.forum.attribute('apiUrl') + '/profile-fields/' + this.attrs.field.id(),
        method: 'PATCH',
        body: {
          data: {
            type: 'profile-fields',
            id: this.attrs.field.id(),
            attributes: data
          }
        }
      }).then(response => {
        app.store.pushPayload(response);
        return app.store.getById('profile-fields', this.attrs.field.id());
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