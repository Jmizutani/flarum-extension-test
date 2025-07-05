import Page from 'flarum/common/components/Page';
import Button from 'flarum/common/components/Button';
import LoadingIndicator from 'flarum/common/components/LoadingIndicator';
import ProfileFieldModal from './ProfileFieldModal';

export default class ProfileFieldsPage extends Page {
  oninit(vnode) {
    super.oninit(vnode);
    
    this.loading = true;
    this.fields = [];
    
    this.loadFields();
  }

  loadFields() {
    this.loading = true;
    
    app.store.find('profile-fields')
      .then(fields => {
        this.fields = fields.sort((a, b) => a.sortOrder() - b.sortOrder());
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
        <div className="ProfileFieldsPage">
          <LoadingIndicator />
        </div>
      );
    }

    return (
      <div className="ProfileFieldsPage">
        <div className="ProfileFieldsPage-header">
          <div className="container">
            <h2>プロフィールフィールド設定</h2>
            <Button
              className="Button Button--primary"
              onclick={() => this.showModal()}
            >
              新しいフィールドを追加
            </Button>
          </div>
        </div>
        
        <div className="ProfileFieldsPage-content">
          <div className="container">
            {this.fields.length === 0 ? (
              <div className="ProfileFieldsPage-empty">
                <p>プロフィールフィールドが設定されていません。</p>
              </div>
            ) : (
              <div className="ProfileFieldsList">
                {this.fields.map(field => this.fieldItem(field))}
              </div>
            )}
          </div>
        </div>
      </div>
    );
  }

  fieldItem(field) {
    return (
      <div key={field.id()} className="ProfileFieldItem">
        <div className="ProfileFieldItem-info">
          <div className="ProfileFieldItem-label">{field.label()}</div>
          <div className="ProfileFieldItem-details">
            <span className="ProfileFieldItem-name">({field.name()})</span>
            <span className="ProfileFieldItem-type">{field.type()}</span>
            {field.required() && <span className="ProfileFieldItem-required">必須</span>}
          </div>
        </div>
        
        <div className="ProfileFieldItem-controls">
          <Button
            className="Button Button--link"
            onclick={() => this.showModal(field)}
          >
            編集
          </Button>
          <Button
            className="Button Button--link"
            onclick={() => this.deleteField(field)}
          >
            削除
          </Button>
        </div>
      </div>
    );
  }

  showModal(field = null) {
    app.modal.show(ProfileFieldModal, {
      field: field,
      onsubmit: () => this.loadFields()
    });
  }

  deleteField(field) {
    if (confirm(`フィールド「${field.label()}」を削除してもよろしいですか？`)) {
      field.delete()
        .then(() => this.loadFields())
        .catch(error => {
          console.error('Delete error:', error);
        });
    }
  }
}