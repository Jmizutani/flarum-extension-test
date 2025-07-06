import app from 'flarum/admin/app';
import UserProfile from '../common/models/UserProfile';
import ProfileField from '../common/models/ProfileField';
import ProfileFieldsPage from './components/ProfileFieldsPage';

app.initializers.add('junya-user-profile', () => {
  app.store.models['user-profiles'] = UserProfile;
  app.store.models['profile-fields'] = ProfileField;
  
  app.extensionData
    .for('junya-user-profile')
    .registerSetting(function () {
      return <ProfileFieldsPage />;
    });
});