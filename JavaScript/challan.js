function setPage()
{
    if(document.getElementById("v_no").checked)
    {
        document.querySelector(".p1").innerHTML = "Enter vehicle number";
        document.querySelector(".p2").innerHTML = "IND";
        document.querySelector(".p3").placeholder= "DL 01 AB 12XX";
    }
    else if( document.getElementById("c_no").checked)
    {
        document.querySelector(".p1").innerHTML = "Enter Chassis number";
        document.querySelector(".p2").innerHTML = "VIN";
        document.querySelector(".p3").placeholder = "AB123XXXXXXXXX231X";

    }
    else
    {
        document.querySelector(".p1").innerHTML = "Enter license number";
        document.querySelector(".p2").innerHTML = "L NO";
        document.querySelector(".p3").placeholder = "SSRRYYYYNNNNNNN";

    }
}
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const searchType = document.querySelector('input[name="type"]:checked').value;
        const searchValue = document.getElementById('cred').value.trim();
        
        if (!searchValue) {
            alert('Please enter a search value');
            return;
        }
        
        fetchChallanDetails(searchType, searchValue);
    });
});
function fetchChallanDetails(type, value) {
    const url = `../backend/challan_status.php?type=${type}&cred=${encodeURIComponent(value)}`;
    
    fetch(url)
        .then(response => response.json())
        .then(data => {
            if (data.success && data.data) {
                displayChallanDetails(data.data);
            }
        })
        .catch(error => {
            console.error("Fetch error:", error); 
        });
}

function displayChallanDetails(challans) {
    const existingModal = document.querySelector('.challan-modal');
    if (existingModal) {
        existingModal.remove();
    }
    
    const modal = document.createElement('div');
    modal.className = 'challan-modal';
    modal.style.position = 'fixed';
    modal.style.top = '0';
    modal.style.left = '0';
    modal.style.width = '100%';
    modal.style.height = '100%';
    modal.style.backgroundColor = 'rgba(0,0,0,0.8)';
    modal.style.zIndex = '1000';
    modal.style.display = 'flex';
    modal.style.justifyContent = 'center';
    modal.style.alignItems = 'center';
    
    const modalContent = document.createElement('div');
    modalContent.style.backgroundColor = 'white';
    modalContent.style.padding = '20px';
    modalContent.style.borderRadius = '8px';
    modalContent.style.maxWidth = '800px';
    modalContent.style.width = '90%';
    modalContent.style.maxHeight = '90vh';
    modalContent.style.overflow = 'auto';
    modalContent.style.position = 'relative'; 

    const closeButton = document.createElement('button');
    closeButton.innerHTML = '&times;'; 
    closeButton.style.position = 'absolute';
    closeButton.style.top = '10px';
    closeButton.style.right = '10px';
    closeButton.style.background = 'none';
    closeButton.style.border = 'none';
    closeButton.style.fontSize = '24px';
    closeButton.style.cursor = 'pointer';
    closeButton.style.color = '#666';
    closeButton.onclick = function() {
        modal.remove();
    };
    
    let html = `<h2 style="color: #1e3a8a; margin-bottom: 20px;">Challan Details</h2>`;
    
    if (challans.length === 0) {
        html += `<p>No challans found</p>`;
    } else {
        challans.forEach((challan, index) => {
            html += `
                <div style="margin-bottom: 30px; border-bottom: 1px solid #eee; padding-bottom: 20px;">
                    <h3 style="color: #1e3a8a;">Challan #${index + 1}</h3>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-top: 15px;">
                        <div>
                            <p><strong>User Name:</strong> ${challan.user_name}</p>
                            <p><strong>Vehicle Number:</strong> ${challan.vehicle_number}</p>
                            <p><strong>Violation Type:</strong> ${challan.violation_type}</p>
                            <p><strong>Rule Number:</strong> ${challan.rule_number}</p>
                        </div>
                        <div>
                            <p><strong>Violation Date:</strong> ${new Date(challan.violation_date).toLocaleString()}</p>
                            <p><strong>Location:</strong> ${challan.location}</p>
                            <p><strong>Fine Amount:</strong> ₹${challan.fine_amount}</p>
                            <p><strong>Status:</strong> ${challan.status}</p>
                        </div>
                    </div>
                    <div style="margin-top: 10px;">
                        <p><strong>Description:</strong> ${challan.description}</p>
                    </div>
                    ${challan.status === 'Pending' ? 
                        `<a href="pay_fine.php?challan_id=${challan.violation_id}" style="display: inline-block; padding: 8px 16px; background-color: #10b981; color: white; text-decoration: none; border-radius: 4px; cursor: pointer; margin-top: 10px; transition: background-color 0.3s;" 
                           onmouseover="this.style.backgroundColor='#059669'" 
                           onmouseout="this.style.backgroundColor='#10b981'">Pay Now</a>` : 
                        ''}
                </div>
            `;
        });
    }

    modalContent.innerHTML = html;
    modalContent.prepend(closeButton);
    modal.appendChild(modalContent);
    document.body.appendChild(modal);
}