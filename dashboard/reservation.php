<?php
// Check if user is logged in
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$pageTitle = "Reservation Management";
include_once 'includes/header.php';
?>

<div class="dashboard-container">
  <?php include_once 'includes/sidebar.php'; ?>
  
  <main class="dashboard-content">
    <div class="dashboard-header">
      <button id="sidebarToggle" class="sidebar-toggle">
        <i class="icon-menu"></i>
      </button>
      <h1>Reservation Management</h1>
      <div class="user-menu">
        <div class="user-info">
          <img src="../assets/images/user-avatar.jpg" alt="User" class="user-avatar">
          <span class="user-name">Dr. Jane Smith</span>
        </div>
        <div class="dropdown-menu">
          <a href="profile.php"><i class="icon-user"></i> Profile</a>
          <a href="settings.php"><i class="icon-settings"></i> Settings</a>
          <a href="logout.php"><i class="icon-log-out"></i> Logout</a>
        </div>
      </div>
    </div>
    
    <div class="content-header">
      <div class="search-filter">
        <div class="search-box">
          <input type="text" id="reservationSearch" placeholder="Search reservations...">
          <button class="search-btn"><i class="icon-search"></i></button>
        </div>
        <div class="filter-options">
          <select id="filterStatus">
            <option value="">All Statuses</option>
            <option value="pending">Pending</option>
            <option value="confirmed">Confirmed</option>
            <option value="completed">Completed</option>
            <option value="cancelled">Cancelled</option>
          </select>
          <select id="filterDate">
            <option value="">All Dates</option>
            <option value="today">Today</option>
            <option value="tomorrow">Tomorrow</option>
            <option value="this-week">This Week</option>
            <option value="next-week">Next Week</option>
            <option value="this-month">This Month</option>
          </select>
        </div>
      </div>
      <button class="btn btn-primary" id="createReservationBtn">
        <i class="icon-plus"></i> New Reservation
      </button>
    </div>
    
    <div class="reservation-stats">
      <div class="stat-card">
        <div class="stat-icon">
          <i class="icon-clock"></i>
        </div>
        <div class="stat-content">
          <h3>Pending</h3>
          <p class="stat-value">12</p>
        </div>
      </div>
      
      <div class="stat-card">
        <div class="stat-icon">
          <i class="icon-check-circle"></i>
        </div>
        <div class="stat-content">
          <h3>Confirmed</h3>
          <p class="stat-value">28</p>
        </div>
      </div>
      
      <div class="stat-card">
        <div class="stat-icon">
          <i class="icon-check-square"></i>
        </div>
        <div class="stat-content">
          <h3>Completed</h3>
          <p class="stat-value">45</p>
        </div>
      </div>
      
      <div class="stat-card">
        <div class="stat-icon">
          <i class="icon-x-circle"></i>
        </div>
        <div class="stat-content">
          <h3>Cancelled</h3>
          <p class="stat-value">7</p>
        </div>
      </div>
    </div>
    
    <div class="reservations-table-container">
      <table class="data-table reservations-table">
        <thead>
          <tr>
            <th>ID</th>
            <th>Patient</th>
            <th>Service</th>
            <th>Date & Time</th>
            <th>Doctor</th>
            <th>Status</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>RES-2023-001</td>
            <td class="patient-name">
              <img src="../assets/images/patient1.jpg" alt="Patient" class="patient-avatar-sm">
              <div>
                <span class="name">John Doe</span>
                <span class="email">john.doe@example.com</span>
              </div>
            </td>
            <td>Teeth Cleaning</td>
            <td>
              <div class="reservation-datetime">
                <div class="date">May 20, 2023</div>
                <div class="time">9:00 AM</div>
              </div>
            </td>
            <td>Dr. Jane Smith</td>
            <td><span class="status-badge pending">Pending</span></td>
            <td class="actions">
              <button class="action-btn view-btn" title="View Details"><i class="icon-eye"></i></button>
              <button class="action-btn edit-btn" title="Edit Reservation"><i class="icon-edit"></i></button>
              <button class="action-btn" title="Confirm Reservation"><i class="icon-check"></i></button>
              <button class="action-btn" title="Cancel Reservation"><i class="icon-x"></i></button>
            </td>
          </tr>
          <tr>
            <td>RES-2023-002</td>
            <td class="patient-name">
              <img src="../assets/images/patient2.jpg" alt="Patient" class="patient-avatar-sm">
              <div>
                <span class="name">Sarah Johnson</span>
                <span class="email">sarah.j@example.com</span>
              </div>
            </td>
            <td>Root Canal</td>
            <td>
              <div class="reservation-datetime">
                <div class="date">May 20, 2023</div>
                <div class="time">11:00 AM</div>
              </div>
            </td>
            <td>Dr. Jane Smith</td>
            <td><span class="status-badge confirmed">Confirmed</span></td>
            <td class="actions">
              <button class="action-btn view-btn" title="View Details"><i class="icon-eye"></i></button>
              <button class="action-btn edit-btn" title="Edit Reservation"><i class="icon-edit"></i></button>
              <button class="action-btn" title="Mark as Completed"><i class="icon-check-square"></i></button>
              <button class="action-btn" title="Cancel Reservation"><i class="icon-x"></i></button>
            </td>
          </tr>
          <tr>
            <td>RES-2023-003</td>
            <td class="patient-name">
              <img src="../assets/images/patient3.jpg" alt="Patient" class="patient-avatar-sm">
              <div>
                <span class="name">Michael Brown</span>
                <span class="email">m.brown@example.com</span>
              </div>
            </td>
            <td>Dental Filling</td>
            <td>
              <div class="reservation-datetime">
                <div class="date">May 20, 2023</div>
                <div class="time">1:30 PM</div>
              </div>
            </td>
            <td>Dr. Jane Smith</td>
            <td><span class="status-badge confirmed">Confirmed</span></td>
            <td class="actions">
              <button class="action-btn view-btn" title="View Details"><i class="icon-eye"></i></button>
              <button class="action-btn edit-btn" title="Edit Reservation"><i class="icon-edit"></i></button>
              <button class="action-btn" title="Mark as Completed"><i class="icon-check-square"></i></button>
              <button class="action-btn" title="Cancel Reservation"><i class="icon-x"></i></button>
            </td>
          </tr>
          <tr>
            <td>RES-2023-004</td>
            <td class="patient-name">
              <img src="../assets/images/patient4.jpg" alt="Patient" class="patient-avatar-sm">
              <div>
                <span class="name">Emily Davis</span>
                <span class="email">emily.d@example.com</span>
              </div>
            </td>
            <td>Crown Fitting</td>
            <td>
              <div class="reservation-datetime">
                <div class="date">May 20, 2023</div>
                <div class="time">3:30 PM</div>
              </div>
            </td>
            <td>Dr. Jane Smith</td>
            <td><span class="status-badge confirmed">Confirmed</span></td>
            <td class="actions">
              <button class="action-btn view-btn" title="View Details"><i class="icon-eye"></i></button>
              <button class="action-btn edit-btn" title="Edit Reservation"><i class="icon-edit"></i></button>
              <button class="action-btn" title="Mark as Completed"><i class="icon-check-square"></i></button>
              <button class="action-btn" title="Cancel Reservation"><i class="icon-x"></i></button>
            </td>
          </tr>
          <tr>
            <td>RES-2023-005</td>
            <td class="patient-name">
              <img src="../assets/images/patient5.jpg" alt="Patient" class="patient-avatar-sm">
              <div>
                <span class="name">Robert Wilson</span>
                <span class="email">r.wilson@example.com</span>
              </div>
            </td>
            <td>Braces Adjustment</td>
            <td>
              <div class="reservation-datetime">
                <div class="date">May 21, 2023</div>
                <div class="time">9:00 AM</div>
              </div>
            </td>
            <td>Dr. John Davis</td>
            <td><span class="status-badge pending">Pending</span></td>
            <td class="actions">
              <button class="action-btn view-btn" title="View Details"><i class="icon-eye"></i></button>
              <button class="action-btn edit-btn" title="Edit Reservation"><i class="icon-edit"></i></button>
              <button class="action-btn" title="Confirm Reservation"><i class="icon-check"></i></button>
              <button class="action-btn" title="Cancel Reservation"><i class="icon-x"></i></button>
            </td>
          </tr>
          <tr>
            <td>RES-2023-006</td>
            <td class="patient-name">
              <img src="../assets/images/patient6.jpg" alt="Patient" class="patient-avatar-sm">
              <div>
                <span class="name">Jennifer Lee</span>
                <span class="email">j.lee@example.com</span>
              </div>
            </td>
            <td>Consultation</td>
            <td>
              <div class="reservation-datetime">
                <div class="date">May 21, 2023</div>
                <div class="time">10:30 AM</div>
              </div>
            </td>
            <td>Dr. John Davis</td>
            <td><span class="status-badge confirmed">Confirmed</span></td>
            <td class="actions">
              <button class="action-btn view-btn" title="View Details"><i class="icon-eye"></i></button>
              <button class="action-btn edit-btn" title="Edit Reservation"><i class="icon-edit"></i></button>
              <button class="action-btn" title="Mark as Completed"><i class="icon-check-square"></i></button>
              <button class="action-btn" title="Cancel Reservation"><i class="icon-x"></i></button>
            </td>
          </tr>
          <tr>
            <td>RES-2023-007</td>
            <td class="patient-name">
              <img src="../assets/images/patient7.jpg" alt="Patient" class="patient-avatar-sm">
              <div>
                <span class="name">David Miller</span>
                <span class="email">d.miller@example.com</span>
              </div>
            </td>
            <td>Braces Installation</td>
            <td>
              <div class="reservation-datetime">
                <div class="date">May 21, 2023</div>
                <div class="time">1:30 PM</div>
              </div>
            </td>
            <td>Dr. John Davis</td>
            <td><span class="status-badge cancelled">Cancelled</span></td>
            <td class="actions">
              <button class="action-btn view-btn" title="View Details"><i class="icon-eye"></i></button>
              <button class="action-btn edit-btn" title="Edit Reservation"><i class="icon-edit"></i></button>
              <button class="action-btn" title="Restore Reservation"><i class="icon-refresh-cw"></i></button>
              <button class="action-btn delete-btn" title="Delete Reservation"><i class="icon-trash"></i></button>
            </td>
          </tr>
        </tbody>
      </table>
      
      <div class="pagination">
        <button class="pagination-btn prev" disabled><i class="icon-chevron-left"></i></button>
        <div class="pagination-pages">
          <button class="pagination-page active">1</button>
          <button class="pagination-page">2</button>
          <button class="pagination-page">3</button>
          <span class="pagination-ellipsis">...</span>
          <button class="pagination-page">10</button>
        </div>
        <button class="pagination-btn next"><i class="icon-chevron-right"></i></button>
      </div>
    </div>
    
    <!-- Reservation Modal -->
    <div id="reservationModal" class="modal">
      <div class="modal-content">
        <div class="modal-header">
          <h2 id="modalTitle">New Reservation</h2>
          <button class="modal-close">&times;</button>
        </div>
        <div class="modal-body">
          <form id="reservationForm">
            <div class="form-row">
              <div class="form-group">
                <label for="patientSelect">Patient</label>
                <select id="patientSelect" name="patient" required>
                  <option value="">Select Patient</option>
                  <option value="1">John Doe</option>
                  <option value="2">Sarah Johnson</option>
                  <option value="3">Michael Brown</option>
                  <option value="4">Emily Davis</option>
                  <option value="5">Robert Wilson</option>
                </select>
              </div>
              <div class="form-group">
                <button type="button" class="btn btn-outline btn-sm">
                  <i class="icon-plus"></i> New Patient
                </button>
              </div>
            </div>
            
            <div class="form-row">
              <div class="form-group">
                <label for="appointmentDate">Date</label>
                <input type="date" id="appointmentDate" name="date" required>
              </div>
              <div class="form-group">
                <label for="appointmentTime">Time</label>
                <select id="appointmentTime" name="time" required>
                  <option value="">Select Time</option>
                  <option value="9:00">9:00 AM</option>
                  <option value="9:30">9:30 AM</option>
                  <option value="10:00">10:00 AM</option>
                  <option value="10:30">10:30 AM</option>
                  <option value="11:00">11:00 AM</option>
                  <option value="11:30">11:30 AM</option>
                  <option value="1:00">1:00 PM</option>
                  <option value="1:30">1:30 PM</option>
                  <option value="2:00">2:00 PM</option>
                  <option value="2:30">2:30 PM</option>
                  <option value="3:00">3:00 PM</option>
                  <option value="3:30">3:30 PM</option>
                  <option value="4:00">4:00 PM</option>
                  <option value="4:30">4:30 PM</option>
                </select>
              </div>
            </div>
            
            <div class="form-row">
              <div class="form-group">
                <label for="appointmentDuration">Duration</label>
                <select id="appointmentDuration" name="duration" required>
                  <option value="30">30 minutes</option>
                  <option value="60" selected>1 hour</option>
                  <option value="90">1.5 hours</option>
                  <option value="120">2 hours</option>
                </select>
              </div>
              <div class="form-group">
                <label for="doctorSelect">Doctor</label>
                <select id="doctorSelect" name="doctor" required>
                  <option value="">Select Doctor</option>
                  <option value="1">Dr. Jane Smith</option>
                  <option value="2">Dr. John Davis</option>
                  <option value="3">Dr. Sarah Wilson</option>
                </select>
              </div>
            </div>
            
            <div class="form-group">
              <label for="appointmentType">Service</label>
              <select id="appointmentType" name="type" required>
                <option value="">Select Service</option>
                <option value="check-up">Check-up</option>
                <option value="cleaning">Teeth Cleaning</option>
                <option value="filling">Dental Filling</option>
                <option value="root-canal">Root Canal</option>
                <option value="crown">Crown Fitting</option>
                <option value="extraction">Tooth Extraction</option>
                <option value="consultation">Consultation</option>
                <option value="follow-up">Follow-up</option>
              </select>
            </div>
            
            <div class="form-group">
              <label for="reservationStatus">Status</label>
              <select id="reservationStatus" name="status" required>
                <option value="pending">Pending</option>
                <option value="confirmed">Confirmed</option>
                <option value="completed">Completed</option>
                <option value="cancelled">Cancelled</option>
              </select>
            </div>
            
            <div class="form-group">
              <label for="reservationNotes">Notes</label>
              <textarea id="reservationNotes" name="notes" rows="3"></textarea>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button class="btn btn-outline" id="cancelReservationBtn">Cancel</button>
          <button class="btn btn-primary" id="saveReservationBtn">Save Reservation</button>
        </div>
      </div>
    </div>
  </main>
</div>

<?php include_once 'includes/footer.php'; ?>