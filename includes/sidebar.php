<aside id="sidebar" class="sticky top-0 left-0 h-screen w-64 bg-white shadow-lg flex flex-col px-4 py-6 z-50">
  <!-- Sidebar Header -->
  <div class="px-4 mb-8">
    <h2 class="text-2xl font-bold text-blue-900">Admin Panel</h2>
  </div>

  <!-- Navigation Links -->
  <nav class="flex-1">
    <ul class="space-y-2">
      <li>
        <a href="../admin_functions/admin_dashboard.php" class="flex items-center px-4 py-3 text-blue-900 hover:bg-blue-100 rounded-lg transition">
          <span>Dashboard</span>
        </a>
      </li>
      <li>
        <a href="../admin_functions/vehicle_registration.php" class="flex items-center px-4 py-3 text-blue-900 hover:bg-blue-100 rounded-lg transition">
          <span>Vehicle Registration</span>
        </a>
      </li>
      <li>
        <a href="../admin_functions/officer_approvals.php" class="flex items-center px-4 py-3 text-blue-900 hover:bg-blue-100 rounded-lg transition">
          <span>Officer Approvals</span>
        </a>
      </li>
      <li>
        <a href="" class="flex items-center px-4 py-3 text-blue-900 hover:bg-blue-100 rounded-lg transition">
          <span>Appeals</span>
        </a>
      </li>
      <li>
        <a href="../admin_functions/report.php" class="flex items-center px-4 py-3 text-blue-900 hover:bg-blue-100 rounded-lg transition">
          <span>Reports</span>
        </a>
      </li>
    </ul>
  </nav>

  <!-- Logout Button -->
  <div class="mt-auto px-4 pt-4 border-t border-gray-200">
    <a href="../../Backend/logout.php" class="block w-full text-center bg-red-600 text-white font-medium py-2 rounded hover:bg-red-700 transition">
      Logout
    </a>
  </div>
</aside>

<script>
// Simple JavaScript for active link highlighting
document.addEventListener('DOMContentLoaded', function() {
  const currentPage = window.location.pathname.split('/').pop() || '';
  const navLinks = document.querySelectorAll('#sidebar nav a');
  
  navLinks.forEach(link => {
    const linkPage = link.getAttribute('href').split('/').pop();
    if (currentPage === linkPage || 
        (link.getAttribute('href').includes('?page=') && 
         window.location.search.includes(link.getAttribute('href').split('=')[1]))) {
      link.classList.add('bg-blue-100', 'font-semibold');
      link.classList.remove('hover:bg-blue-100');
    }
  });
  
  // Mobile toggle functionality (optional)
  const sidebar = document.getElementById('sidebar');
  const toggleBtn = document.createElement('button');
  toggleBtn.innerHTML = '☰';
  toggleBtn.className = 'lg:hidden fixed top-4 left-4 z-50 bg-white p-2 rounded shadow';
  toggleBtn.onclick = function() {
    sidebar.classList.toggle('hidden');
  };
  document.body.appendChild(toggleBtn);
  
  // Hide sidebar on mobile by default
  if (window.innerWidth < 1024) {
    sidebar.classList.add('hidden');
  }
});
</script>