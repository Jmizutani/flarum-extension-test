import Model from 'flarum/common/Model';

export default class ProfileField extends Model {
  name = Model.attribute('name');
  label = Model.attribute('label');
  type = Model.attribute('type');
  required = Model.attribute('required');
  sortOrder = Model.attribute('sortOrder');
  isActive = Model.attribute('isActive');
  createdAt = Model.attribute('createdAt', Model.transformDate);
  updatedAt = Model.attribute('updatedAt', Model.transformDate);
}