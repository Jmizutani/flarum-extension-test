import Model from 'flarum/common/Model';

export default class SocialLink extends Model {}

Object.assign(SocialLink.prototype, {
  name: Model.attribute('name'),
  label: Model.attribute('label'),
  iconUrl: Model.attribute('iconUrl'),
  urlPattern: Model.attribute('urlPattern'),
  sortOrder: Model.attribute('sortOrder'),
  isActive: Model.attribute('isActive'),
  createdAt: Model.attribute('createdAt'),
  updatedAt: Model.attribute('updatedAt')
});