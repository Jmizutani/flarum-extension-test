import Model from 'flarum/common/Model';

export default class UserProfile extends Model {
  userId = Model.attribute('userId');
  introduction = Model.attribute('introduction');
  childcareSituation = Model.attribute('childcareSituation');
  careSituation = Model.attribute('careSituation');
  facebookUrl = Model.attribute('facebookUrl');
  xUrl = Model.attribute('xUrl');
  instagramUrl = Model.attribute('instagramUrl');
  isVisible = Model.attribute('isVisible');
  createdAt = Model.attribute('createdAt', Model.transformDate);
  updatedAt = Model.attribute('updatedAt', Model.transformDate);
  
  user = Model.hasOne('user');
}