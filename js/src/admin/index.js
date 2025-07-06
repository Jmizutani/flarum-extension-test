import app from 'flarum/admin/app';
import UserProfile from '../common/models/UserProfile';
import ProfileField from '../common/models/ProfileField';
import ProfileFieldsPage from './components/ProfileFieldsPage';

app.initializers.add('junya-user-profile', () => {
  app.store.models['user-profiles'] = UserProfile;
  app.store.models['profile-fields'] = ProfileField;
  
  app.extensionData
    .for('junya-user-profile')
    .registerSetting({
      setting: 'user-profile-enabled',
      label: 'プロフィール機能を有効にする',
      type: 'boolean',
      default: true
    })
    .registerSetting(function () {
      return <ProfileFieldsPage />;
    });
});