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
            <label>名前 (内部用)</label>
            <input
              className="FormControl"
              type="text"
              placeholder="facebook, x, instagram など"
              bidi={this.name}
            />
            <div className="helpText">
              システム内部で使用される識別子です。英数字とアンダースコアのみ使用可能。
            </div>
          </div>

          <div className="Form-group">
            <label>表示名</label>
            <input
              className="FormControl"
              type="text"
              placeholder="Facebook, X (Twitter), Instagram など"
              bidi={this.label}
            />
          </div>

          <div className="Form-group">
            <label>アイコンURL</label>
            <input
              className="FormControl"
              type="url"
              placeholder="https://example.com/icon.svg"
              bidi={this.iconUrl}
            />
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
            <div>
              <label className="checkbox">
                <input
                  type="checkbox"
                  checked={this.isActive()}
                  onchange={(e) => this.isActive(e.target.checked)}
                />
                有効
              </label>
            </div>
          </div>

          <div className="Form-group">
            {Button.component(
              {
                type: 'submit',
                className: 'Button Button--primary Button--block',
                loading: this.loading,
              },
              this.socialLink ? '更新' : '作成'
            )}
          </div>
        </div>
      </div>
    );
  }

  onsubmit(e) {
    e.preventDefault();

    this.loading = true;

    const data = {
      name: this.name(),
      label: this.label(),
      iconUrl: this.iconUrl(),
      placeholder: this.placeholder(),
      sortOrder: parseInt(this.sortOrder()) || 0,
      isActive: this.isActive()
    };

    const promise = this.socialLink
      ? this.socialLink.save(data)
      : app.store.createRecord('social-links').save(data);

    promise
      .then(() => {
        this.hide();
        if (this.attrs.onsubmit) {
          this.attrs.onsubmit();
        }
      })
      .catch(() => {})
      .then(() => {
        this.loading = false;
        m.redraw();
      });
  }
}