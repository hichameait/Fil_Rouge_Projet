<?php
// Check if user is logged in
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$pageTitle = "Settings";
include '../includes/header.php';
?>

<div class="flex min-h-screen bg-gray-100">
  <?php include_once 'includes/sidebar.php'; ?>
  <main class="flex-1 p-6">
    <div class="max-w-5xl mx-auto">
      <h1 class="text-3xl font-bold mb-8">Settings</h1>
      <div class="flex gap-8">
        <!-- Sidebar -->
        <aside class="w-64 bg-white rounded-lg shadow p-4 hidden md:block">
          <ul class="space-y-2">
            <li class="settings-nav-item active cursor-pointer flex items-center gap-2 px-3 py-2 rounded hover:bg-blue-50" data-tab="general">
              <i class="icon-settings"></i> <span>General</span>
            </li>
            <li class="settings-nav-item cursor-pointer flex items-center gap-2 px-3 py-2 rounded hover:bg-blue-50" data-tab="profile">
              <i class="icon-user"></i> <span>Profile</span>
            </li>
            <li class="settings-nav-item cursor-pointer flex items-center gap-2 px-3 py-2 rounded hover:bg-blue-50" data-tab="clinic">
              <i class="icon-home"></i> <span>Clinic Information</span>
            </li>
            <li class="settings-nav-item cursor-pointer flex items-center gap-2 px-3 py-2 rounded hover:bg-blue-50" data-tab="staff">
              <i class="icon-users"></i> <span>Staff Management</span>
            </li>
            <li class="settings-nav-item cursor-pointer flex items-center gap-2 px-3 py-2 rounded hover:bg-blue-50" data-tab="appointments">
              <i class="icon-calendar"></i> <span>Appointment Settings</span>
            </li>
            <li class="settings-nav-item cursor-pointer flex items-center gap-2 px-3 py-2 rounded hover:bg-blue-50" data-tab="billing">
              <i class="icon-credit-card"></i> <span>Billing & Payments</span>
            </li>
            <li class="settings-nav-item cursor-pointer flex items-center gap-2 px-3 py-2 rounded hover:bg-blue-50" data-tab="notifications">
              <i class="icon-bell"></i> <span>Notifications</span>
            </li>
            <li class="settings-nav-item cursor-pointer flex items-center gap-2 px-3 py-2 rounded hover:bg-blue-50" data-tab="security">
              <i class="icon-lock"></i> <span>Security</span>
            </li>
            <li class="settings-nav-item cursor-pointer flex items-center gap-2 px-3 py-2 rounded hover:bg-blue-50" data-tab="integrations">
              <i class="icon-link"></i> <span>Integrations</span>
            </li>
            <li class="settings-nav-item cursor-pointer flex items-center gap-2 px-3 py-2 rounded hover:bg-blue-50" data-tab="templates">
              <i class="icon-mail"></i> <span>Communication Templates</span>
            </li>
          </ul>
        </aside>
        <!-- Content -->
        <section class="flex-1">
          <!-- General Tab -->
          <div class="settings-tab active" id="general-tab">
            <div class="bg-white rounded-lg shadow p-6 mb-8">
              <h2 class="text-xl font-semibold mb-4">General Settings</h2>
              <form id="generalSettingsForm" class="space-y-4">
                <div>
                  <label for="language" class="block font-medium mb-1">Language</label>
                  <select id="language" name="language" class="w-full border rounded px-3 py-2">
                    <option value="en">English</option>
                    <option value="es">Spanish</option>
                    <option value="fr">French</option>
                    <option value="de">German</option>
                  </select>
                </div>
                <div>
                  <label for="timezone" class="block font-medium mb-1">Timezone</label>
                  <select id="timezone" name="timezone" class="w-full border rounded px-3 py-2">
                    <option value="UTC-8">Pacific Time (UTC-8)</option>
                    <option value="UTC-7">Mountain Time (UTC-7)</option>
                    <option value="UTC-6">Central Time (UTC-6)</option>
                    <option value="UTC-5" selected>Eastern Time (UTC-5)</option>
                    <option value="UTC-4">Atlantic Time (UTC-4)</option>
                    <option value="UTC+0">UTC</option>
                  </select>
                </div>
                <div>
                  <label for="dateFormat" class="block font-medium mb-1">Date Format</label>
                  <select id="dateFormat" name="dateFormat" class="w-full border rounded px-3 py-2">
                    <option value="MM/DD/YYYY" selected>MM/DD/YYYY</option>
                    <option value="DD/MM/YYYY">DD/MM/YYYY</option>
                    <option value="YYYY-MM-DD">YYYY-MM-DD</option>
                  </select>
                </div>
                <div>
                  <label for="timeFormat" class="block font-medium mb-1">Time Format</label>
                  <select id="timeFormat" name="timeFormat" class="w-full border rounded px-3 py-2">
                    <option value="12" selected>12-hour (1:30 PM)</option>
                    <option value="24">24-hour (13:30)</option>
                  </select>
                </div>
                <div>
                  <label class="block font-medium mb-1">Theme</label>
                  <div class="flex gap-4">
                    <label class="flex items-center gap-2">
                      <input type="radio" id="lightTheme" name="theme" value="light" checked>
                      <span>Light</span>
                    </label>
                    <label class="flex items-center gap-2">
                      <input type="radio" id="darkTheme" name="theme" value="dark">
                      <span>Dark</span>
                    </label>
                    <label class="flex items-center gap-2">
                      <input type="radio" id="systemTheme" name="theme" value="system">
                      <span>System</span>
                    </label>
                  </div>
                </div>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded">Save Changes</button>
              </form>
            </div>
          </div>
          <!-- Profile Tab -->
          <div class="settings-tab hidden" id="profile-tab">
            <div class="bg-white rounded-lg shadow p-6 mb-8">
              <h2 class="text-xl font-semibold mb-4">Profile Settings</h2>
              <form id="profileSettingsForm" class="space-y-4">
                <div class="flex items-center gap-6 mb-4">
                  <img src="../assets/images/user-avatar.jpg" alt="Profile Avatar" class="w-20 h-20 rounded-full border">
                  <div>
                    <button type="button" class="bg-gray-100 border px-3 py-1 rounded mr-2">Change Photo</button>
                    <button type="button" class="bg-gray-100 border px-3 py-1 rounded">Remove</button>
                  </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                  <div>
                    <label for="firstName" class="block font-medium mb-1">First Name</label>
                    <input type="text" id="firstName" name="firstName" class="w-full border rounded px-3 py-2" value="Jane" required>
                  </div>
                  <div>
                    <label for="lastName" class="block font-medium mb-1">Last Name</label>
                    <input type="text" id="lastName" name="lastName" class="w-full border rounded px-3 py-2" value="Smith" required>
                  </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                  <div>
                    <label for="email" class="block font-medium mb-1">Email</label>
                    <input type="email" id="email" name="email" class="w-full border rounded px-3 py-2" value="jane.smith@example.com" required>
                  </div>
                  <div>
                    <label for="phone" class="block font-medium mb-1">Phone</label>
                    <input type="tel" id="phone" name="phone" class="w-full border rounded px-3 py-2" value="(123) 456-7890">
                  </div>
                </div>
                <div>
                  <label for="specialization" class="block font-medium mb-1">Specialization</label>
                  <input type="text" id="specialization" name="specialization" class="w-full border rounded px-3 py-2" value="General Dentist">
                </div>
                <div>
                  <label for="bio" class="block font-medium mb-1">Bio</label>
                  <textarea id="bio" name="bio" rows="4" class="w-full border rounded px-3 py-2">Dr. Jane Smith is a general dentist with over 10 years of experience. She specializes in preventive care and cosmetic dentistry, and is dedicated to providing comfortable and comprehensive dental care for patients of all ages.</textarea>
                </div>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded">Save Changes</button>
              </form>
            </div>
          </div>
          <!-- Clinic Tab -->
          <div class="settings-tab hidden" id="clinic-tab">
            <div class="bg-white rounded-lg shadow p-6 mb-8">
              <h2 class="text-xl font-semibold mb-4">Clinic Information</h2>
              <form id="clinicSettingsForm" class="space-y-4">
                <div>
                  <label for="clinicName" class="block font-medium mb-1">Clinic Name</label>
                  <input type="text" id="clinicName" name="clinicName" class="w-full border rounded px-3 py-2" value="Dental Clinic" required>
                </div>
                <div>
                  <label for="clinicAddress" class="block font-medium mb-1">Address</label>
                  <textarea id="clinicAddress" name="clinicAddress" rows="3" class="w-full border rounded px-3 py-2" required>123 Dental Street, City, State 12345</textarea>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                  <div>
                    <label for="clinicPhone" class="block font-medium mb-1">Phone</label>
                    <input type="tel" id="clinicPhone" name="clinicPhone" class="w-full border rounded px-3 py-2" value="(123) 456-7890" required>
                  </div>
                  <div>
                    <label for="clinicEmail" class="block font-medium mb-1">Email</label>
                    <input type="email" id="clinicEmail" name="clinicEmail" class="w-full border rounded px-3 py-2" value="contact@dentalclinic.com" required>
                  </div>
                </div>
                <div>
                  <label for="clinicWebsite" class="block font-medium mb-1">Website</label>
                  <input type="url" id="clinicWebsite" name="clinicWebsite" class="w-full border rounded px-3 py-2" value="https://www.dentalclinic.com">
                </div>
                <div>
                  <label class="block font-medium mb-1">Business Hours</label>
                  <div class="grid grid-cols-1 gap-2">
                    <!-- Example for Monday, repeat for other days as needed -->
                    <div class="flex items-center gap-2">
                      <span class="w-24">Monday</span>
                      <select name="mondayStart" class="border rounded px-2 py-1">
                        <option value="closed">Closed</option>
                        <option value="9:00" selected>9:00 AM</option>
                        <option value="10:00">10:00 AM</option>
                      </select>
                      <span>to</span>
                      <select name="mondayEnd" class="border rounded px-2 py-1">
                        <option value="closed">Closed</option>
                        <option value="17:00">5:00 PM</option>
                        <option value="18:00" selected>6:00 PM</option>
                      </select>
                    </div>
                    <!-- Repeat for other days... (for brevity, not all days are shown here) -->
                  </div>
                </div>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded">Save Changes</button>
              </form>
            </div>
          </div>
          <!-- Staff Tab -->
          <div class="settings-tab hidden" id="staff-tab">
            <div class="bg-white rounded-lg shadow p-6 mb-8">
              <h2 class="text-xl font-semibold mb-4">Staff Management</h2>
              <p class="text-gray-600">Manage your clinic staff members and their permissions.</p>
            </div>
          </div>
          <!-- Appointments Tab -->
          <div class="settings-tab hidden" id="appointments-tab">
            <div class="bg-white rounded-lg shadow p-6 mb-8">
              <h2 class="text-xl font-semibold mb-4">Appointment Settings</h2>
              <p class="text-gray-600">Configure appointment scheduling, reminders, and cancellation policies.</p>
            </div>
          </div>
          <!-- Billing Tab -->
          <div class="settings-tab hidden" id="billing-tab">
            <div class="bg-white rounded-lg shadow p-6 mb-8">
              <h2 class="text-xl font-semibold mb-4">Billing & Payments</h2>
              <p class="text-gray-600">Configure payment methods, invoicing, and financial settings.</p>
            </div>
          </div>
          <!-- Notifications Tab -->
          <div class="settings-tab hidden" id="notifications-tab">
            <div class="bg-white rounded-lg shadow p-6 mb-8">
              <h2 class="text-xl font-semibold mb-4">Notifications</h2>
              <p class="text-gray-600">Configure email and SMS notifications for appointments, reminders, and system alerts.</p>
            </div>
          </div>
          <!-- Security Tab -->
          <div class="settings-tab hidden" id="security-tab">
            <div class="bg-white rounded-lg shadow p-6 mb-8">
              <h2 class="text-xl font-semibold mb-4">Security</h2>
              <p class="text-gray-600">Manage password, two-factor authentication, and account security settings.</p>
            </div>
          </div>
          <!-- Integrations Tab -->
          <div class="settings-tab hidden" id="integrations-tab">
            <div class="bg-white rounded-lg shadow p-6 mb-8">
              <h2 class="text-xl font-semibold mb-4">Integrations</h2>
              <p class="text-gray-600">Connect with third-party services and applications.</p>
            </div>
          </div>
          <!-- Templates Tab -->
          <div class="settings-tab hidden" id="templates-tab">
            <div class="bg-white rounded-lg shadow p-6 mb-8">
              <h2 class="text-xl font-semibold mb-4">Edit Communication Templates</h2>
              <div class="flex gap-2 mb-4">
                <button class="tab-btn active bg-blue-100 text-blue-700 px-4 py-2 rounded" data-tab="email">Email</button>
                <button class="tab-btn bg-gray-100 text-gray-700 px-4 py-2 rounded" data-tab="sms">SMS</button>
                <button class="tab-btn bg-gray-100 text-gray-700 px-4 py-2 rounded" data-tab="whatsapp">WhatsApp</button>
              </div>
              <div class="tab-content" id="email-template">
                <label class="block font-medium mb-2">Email Template (HTML)</label>
                <textarea class="w-full border rounded p-2 mb-4" rows="8" name="email_template"></textarea>
                <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">Save Email Template</button>
              </div>
              <div class="tab-content hidden" id="sms-template">
                <label class="block font-medium mb-2">SMS Text</label>
                <textarea class="w-full border rounded p-2 mb-4" rows="4" name="sms_template"></textarea>
                <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">Save SMS Template</button>
              </div>
              <div class="tab-content hidden" id="whatsapp-template">
                <label class="block font-medium mb-2">Enable WhatsApp</label>
                <input type="checkbox" class="mb-4" name="whatsapp_enabled">
                <label class="block font-medium mb-2">WhatsApp Message</label>
                <textarea class="w-full border rounded p-2 mb-4" rows="4" name="whatsapp_template"></textarea>
                <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">Save WhatsApp Template</button>
              </div>
            </div>
          </div>
        </section>
      </div>
    </div>
  </main>
</div>

<script>
// Tab switching logic for templates and sidebar
const tabBtns = document.querySelectorAll('.tab-btn');
const tabContents = document.querySelectorAll('.tab-content');
tabBtns.forEach(btn => {
  btn.addEventListener('click', () => {
    tabBtns.forEach(b => b.classList.remove('active', 'bg-blue-100', 'text-blue-700', 'bg-gray-100', 'text-gray-700'));
    btn.classList.add('active', 'bg-blue-100', 'text-blue-700');
    tabContents.forEach(tc => tc.classList.add('hidden'));
    document.getElementById(btn.dataset.tab + '-template').classList.remove('hidden');
  });
});
// Sidebar tab switching
const navItems = document.querySelectorAll('.settings-nav-item');
const settingsTabs = document.querySelectorAll('.settings-tab');
navItems.forEach(item => {
  item.addEventListener('click', () => {
    navItems.forEach(i => i.classList.remove('active', 'bg-blue-100', 'text-blue-700'));
    item.classList.add('active', 'bg-blue-100', 'text-blue-700');
    settingsTabs.forEach(tab => tab.classList.add('hidden'));
    document.getElementById(item.dataset.tab + '-tab').classList.remove('hidden');
  });
});
</script>

<?php include_once 'includes/footer.php'; ?>