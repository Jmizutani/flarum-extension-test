import app from 'flarum/admin/app';
import UserProfile from '../common/models/UserProfile';
import ProfileField from '../common/models/ProfileField';
import SocialLink from '../common/models/SocialLink';
import UserProfileSettingsPage from './components/UserProfileSettingsPage';

app.initializers.add('junya-user-profile', () => {
  app.store.models['user-profiles'] = UserProfile;
  app.store.models['profile-fields'] = ProfileField;
  app.store.models['social-links'] = SocialLink;
  
  app.extensionData
    .for('junya-user-profile')
    .registerSetting(function () {
      return <UserProfileSettingsPage />;
    });
});