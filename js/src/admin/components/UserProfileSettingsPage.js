import Component from 'flarum/common/Component';
import ProfileFieldsSection from './ProfileFieldsSection';
import SocialLinksSection from './SocialLinksSection';

export default class UserProfileSettingsPage extends Component {
  static get route() {
    return 'profile-fields';
  }

  view() {
    return (
      <div className="UserProfileSettingsPage">
        <ProfileFieldsSection />
        <SocialLinksSection />
      </div>
    );
  }
}