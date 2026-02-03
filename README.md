# SoftwareMatrix-GUI
Web interface for the SoftwareMatrix program

This PHP project can do little to nothing without the SoftwareMatrix scripts found at https://github.com/compuvin/SoftwareMatrix

Having the GUI for this project will greatly increase the usage of the SoftwareMatrix program however. The features includes but is not limited to the following:

<ul>
  <li>Visual representation of the data collected</li>
  <li>Create and run custom reports whenever, no need to open up MySQL or wait for an email</li>
  <li>Search (beta) for applications by name or list all by PC</li>
  <li>Quick access to what is important (new software discovered and software needing review)</li>
  <li>Quickly enter information about discovered software in a questionare type form</li>
</ul>

Below is a screenshot of what the GUI looks like:

<img src="http://whatthefoss.com/wp-content/uploads/2019/04/Screenshot-index2.jpg">

## Authentication

Editing pages are protected behind a login screen. User accounts are stored in the `auth_users` table (created automatically if missing). The default admin user is seeded from `auth-config.php` if no admin exists yet.

**Default credentials**
- Username: `admin`
- Password: `ChangeMe123!`

Admins can create additional users from `admin-users.php`.

Work in progress... Check out the progress here: https://github.com/compuvin/SoftwareMatrix-GUI/projects/1

We could use help with the coding and usability for this. Consider creating an issue or pull request if you have suggestions to improve this project.

<a href="https://www.codefactor.io/repository/github/compuvin/softwarematrix-gui"><img src="https://www.codefactor.io/repository/github/compuvin/softwarematrix-gui/badge" alt="CodeFactor" /></a>
