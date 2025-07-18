# CLAUDE.md

必ず日本語で回答してください。

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

This is a Flarum extension that provides customizable user profiles with dynamic fields and social links. Administrators can create, edit, and manage custom profile fields through an admin interface, while users can fill out these fields along with social media links. The extension includes privacy controls and permission-based access.

## Architecture

### Core Database Design
The extension uses a flexible three-table structure:
- **`profile_fields`**: Stores field definitions (name, label, type, required, sort_order, is_active)
- **`profile_field_values`**: Stores user-specific values for each field (user_id, field_id, value)
- **`user_profiles`**: Stores fixed data (social links, visibility settings)

This design allows unlimited custom fields without schema changes.

### Backend (PHP)
- **Models**: `ProfileField`, `ProfileFieldValue`, `UserProfile` with Eloquent relationships
- **API Controllers**: CRUD operations for both profile fields (admin) and user profiles (users)
- **Data Flow**: UserProfile model provides `getFieldValue()` and `setFieldValue()` methods for dynamic field access
- **Migration Strategy**: Automatic migration from fixed fields (introduction, childcare_situation, care_situation) to dynamic fields
- **Permission System**: Admin-only access for field management, user-owner-admin access for profile viewing

### Frontend (JavaScript)
- **Admin Interface**: 
  - `ProfileFieldsPage`: Main admin interface for field management
  - `ProfileFieldModal`: Field creation/editing modal
- **User Interface**:
  - `UserProfileWidget`: Dynamic display component that renders fields based on admin configuration
  - `UserProfileModal`: Dynamic form that generates inputs based on available fields
- **Data Synchronization**: Frontend automatically loads field definitions and renders UI accordingly

### Extension Integration
- **Flarum Integration**: Extends UserPage sidebar, registers admin pages through extensionData
- **Asset Management**: Separate builds for admin and forum with shared common components
- **API Design**: RESTful endpoints following Flarum's JSON API conventions

### File Structure
- **`src/`**: PHP backend code (Models, API Controllers, Serializers)
- **`js/src/`**: JavaScript frontend code with admin, forum, and common modules
- **`migrations/`**: Database schema and default data setup
- **`extend.php`**: Main extension bootstrap file

## Development Commands

```bash
# Install dependencies
composer install
npm install

# Build frontend assets
npm run build          # Production build
npm run build:dev      # Development build
npm run watch          # Watch mode for development

# Clear Flarum cache after PHP changes
php flarum cache:clear

# Extension debugging
php flarum info         # Show Flarum and extension info
php flarum migrate      # Run pending migrations

# No lint or test commands currently configured
```

## API Endpoints

### Profile Fields (Admin)
- `GET /api/profile-fields` - List all profile field definitions
- `POST /api/profile-fields` - Create new profile field
- `PATCH /api/profile-fields/{id}` - Update profile field
- `DELETE /api/profile-fields/{id}` - Delete profile field

### User Profiles
- `GET /api/user-profiles?userId={id}` - Retrieve user profile with privacy checks
- `POST /api/user-profiles` - Create/update user profile (includes customFields object)

### Social Links (Admin)
- `GET /api/social-links` - List all social link definitions
- `POST /api/social-links` - Create new social link
- `PATCH /api/social-links/{id}` - Update social link
- `DELETE /api/social-links/{id}` - Delete social link

## Key Implementation Details

### Dynamic Field Rendering
- Frontend components load field definitions from API and generate forms dynamically
- Field types supported: 'text' (single line) and 'textarea' (multi-line)
- Validation includes required field checking and type-appropriate input rendering

### Data Migration Strategy
- Existing fixed fields automatically migrated to dynamic system
- Default fields (introduction, childcare_situation, care_situation) created during migration
- Old columns safely removed after successful migration

### Permission Model
- Profile field management: Admin only
- Profile viewing: Public (if visible), owner, or admin
- Profile editing: Owner only
- Privacy toggle affects entire profile visibility

# important-instruction-reminders
Do what has been asked; nothing more, nothing less.
NEVER create files unless they're absolutely necessary for achieving your goal.
ALWAYS prefer editing an existing file to creating a new one.
NEVER proactively create documentation files (*.md) or README files. Only create documentation files if explicitly requested by the User.
