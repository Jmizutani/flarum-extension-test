import app from 'flarum/forum/app';
import UserProfile from '../common/models/UserProfile';
import ProfileField from '../common/models/ProfileField';
import UserProfileWidget from '../common/components/UserProfileWidget';
import { extend } from 'flarum/common/extend';
import UserPage from 'flarum/forum/components/UserPage';

app.initializers.add('junya-user-profile', () => {
  app.store.models['user-profiles'] = UserProfile;
  app.store.models['profile-fields'] = ProfileField;
  
  extend(UserPage.prototype, 'sidebarItems', function (items) {
    items.add('userProfile', 
      <UserProfileWidget user={this.user} />,
      10
    );
  });
});