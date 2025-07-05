import app from 'flarum/admin/app';
import UserProfile from '../common/models/UserProfile';
import ProfileField from '../common/models/ProfileField';
import ProfileFieldsPage from './components/ProfileFieldsPage';

app.initializers.add('flarum-user-profile', () => {
  app.store.models['user-profiles'] = UserProfile;
  app.store.models['profile-fields'] = ProfileField;
  
  // Debug logging
  console.log('User Profile Admin extension loaded');
  console.log('app.extensionData:', app.extensionData);
  
  // Register the page with proper configuration
  app.extensionData
    .for('junya/flarum-user-profile')
    .registerPage(ProfileFieldsPage, {
      route: '/profile-fields',
      title: 'プロフィールフィールド設定'
    });
    
  // Verify registration
  console.log('Extension data registered for junya/flarum-user-profile');
  console.log('Extension data:', app.extensionData.getExtensionData('junya/flarum-user-profile'));
});