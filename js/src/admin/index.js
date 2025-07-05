import app from 'flarum/admin/app';
import UserProfile from '../common/models/UserProfile';
import ProfileField from '../common/models/ProfileField';
import ProfileFieldsPage from './components/ProfileFieldsPage';

app.initializers.add('flarum-user-profile', () => {
  app.store.models['user-profiles'] = UserProfile;
  app.store.models['profile-fields'] = ProfileField;
  
  // Debug logging
  console.log('User Profile Admin extension loaded');
  
  // Try both registration methods
  app.extensionData
    .for('junya/flarum-user-profile')
    .registerPage(ProfileFieldsPage);
    
  // Also try registering a simple setting
  app.extensionData
    .for('junya/flarum-user-profile')
    .registerSetting({
      setting: 'user-profile-enabled',
      label: 'プロフィール機能を有効にする',
      type: 'boolean',
      default: true
    });
    
  // Verify registration
  console.log('Extension page registered for junya/flarum-user-profile');
  console.log('Available routes:', app.routes);
});