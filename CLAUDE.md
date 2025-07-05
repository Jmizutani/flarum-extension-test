# CLAUDE.md

必ず日本語で回答してください。

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

This is a Flarum extension for user profiles that allows users to register and display personal information including introduction, childcare/care situations, and social links. The extension includes privacy settings to control visibility.

## Architecture

### Backend (PHP)
- **Model**: `UserProfile` model with database relationships to Flarum's User model
- **API Controllers**: Create/Show controllers for profile data management with permission checks
- **Serializer**: JSON API serialization for frontend consumption
- **Migration**: Database table creation for user profile data storage
- **Extension Registration**: `extend.php` registers routes, models, and frontend assets

### Frontend (JavaScript)
- **Models**: Client-side UserProfile model matching API structure
- **Components**: 
  - `UserProfileWidget`: Display component for user profiles with edit capability
  - `UserProfileModal`: Modal dialog for profile editing
- **Integration**: Extends Flarum's UserPage to add profile widget to sidebar

### Key Features
- Privacy controls (public/private profiles)
- Permission-based access (own profile, admin override)
- Social media links integration
- Responsive design with custom styling

## Development Commands

```bash
# Install dependencies
composer install
npm install

# Build frontend assets
npm run build          # Production build
npm run build:dev      # Development build
npm run watch          # Watch mode for development

# No lint or test commands currently configured
```

## Database Schema

The extension creates a `user_profiles` table with:
- User relationship (foreign key to users table)
- Text fields for introduction, childcare_situation, care_situation
- URL fields for social links (facebook_url, x_url, instagram_url)
- Boolean visibility flag (is_visible)

## API Endpoints

- `POST /api/user-profiles` - Create/update user profile
- `GET /api/user-profiles?userId={id}` - Retrieve user profile with privacy checks
