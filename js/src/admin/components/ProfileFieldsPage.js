import Component from 'flarum/common/Component';
import Button from 'flarum/common/components/Button';
import LoadingIndicator from 'flarum/common/components/LoadingIndicator';
import ProfileFieldModal from './ProfileFieldModal';
import SocialLinksSection from './SocialLinksSection';

export default class ProfileFieldsPage extends Component {
  oninit(vnode) {
    super.oninit(vnode);
    
    this.loading = true;
    this.fields = [];
    
    this.loadFields();
  }
  
  static get route() {
    return 'profile-fields';
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
        <div className="Form-group">
          <LoadingIndicator />
        </div>
      );
    }

    return (
      <div>
        <div className="Form-group">
          <label>プロフィールフィールド管理</label>
          <div className="helpText" style="margin-bottom: 15px;">
            カスタムプロフィールフィールドを作成・管理できます。
          </div>
          
          <div style="margin-bottom: 15px;">
            <Button
              className="Button Button--primary"
              onclick={() => this.showModal()}
            >
              新しいフィールドを追加
            </Button>
          </div>
          
          {this.fields.length === 0 ? (
            <div className="helpText">
              プロフィールフィールドが設定されていません。
            </div>
          ) : (
            <div>
              {this.fields.map(field => this.fieldItem(field))}
            </div>
          )}
        </div>
        
        <SocialLinksSection />
      </div>
    );
  }

  fieldItem(field) {
    return (
      <div 
        key={field.id()} 
        className="ExtensionListItem"
        style="display: flex; justify-content: space-between; align-items: center; padding: 10px; margin-bottom: 5px; border: 1px solid #ddd; border-radius: 4px;"
      >
        <div>
          <div style="font-weight: bold; margin-bottom: 5px;">
            {field.label()}
            {field.required() && <span style="color: red; margin-left: 5px;">*</span>}
          </div>
          <div style="font-size: 12px; color: #666;">
            <span>名前: {field.name()}</span>
            <span style="margin-left: 15px;">タイプ: {field.type()}</span>
          </div>
        </div>
        
        <div>
          <Button
            className="Button Button--link"
            onclick={() => this.showModal(field)}
            style="margin-right: 10px;"
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
      app.request({
        url: app.forum.attribute('apiUrl') + '/profile-fields/' + field.id(),
        method: 'DELETE'
      })
        .then(() => this.loadFields())
        .catch(error => {
          console.error('Delete error:', error);
        });
    }
  }
}