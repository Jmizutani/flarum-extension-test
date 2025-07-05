import app from 'flarum/admin/app';
import UserProfile from '../common/models/UserProfile';

app.initializers.add('flarum-user-profile', () => {
  app.store.models['user-profiles'] = UserProfile;
});