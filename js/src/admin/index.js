import app from 'flarum/admin/app';
import UserProfile from '../common/models/UserProfile';
import ProfileField from '../common/models/ProfileField';
import ProfileFieldsPage from './components/ProfileFieldsPage';

app.initializers.add('flarum-user-profile', () => {
  app.store.models['user-profiles'] = UserProfile;
  app.store.models['profile-fields'] = ProfileField;
  
  // Debug logging
  console.log('User Profile Admin extension loaded');
  
  // Try different extension ID formats
  const extensionIds = [
    'junya/flarum-user-profile',
    'junya-flarum-user-profile'
  ];
  
  extensionIds.forEach(extensionId => {
    console.log(`Trying extension ID: ${extensionId}`);
    
    // Try both registration methods
    app.extensionData
      .for(extensionId)
      .registerPage(ProfileFieldsPage);
      
    // Also try registering a simple setting
    app.extensionData
      .for(extensionId)
      .registerSetting({
        setting: 'user-profile-enabled',
        label: 'プロフィール機能を有効にする',
        type: 'boolean',
        default: true
      });
  });
    
  // Verify registration
  console.log('Extension page registered');
  console.log('Available routes:', app.routes);
});