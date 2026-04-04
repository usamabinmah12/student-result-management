function loadData() {
    let id = document.getElementById("student_id").value;
    let semester = document.getElementById("semester_select").value;
    
    if (!id) {
        document.getElementById("output").innerHTML = "<p style='color:red'>Please enter a Student ID</p>";
        return;
    }
    
    let url = "get_results.php?id=" + encodeURIComponent(id);
    if (semester) url += "&semester=" + encodeURIComponent(semester);
    
    fetch(url)
        .then(res => res.json())
        .then(data => {
            if (data.error) {
                document.getElementById("output").innerHTML = "<p style='color:red'>" + data.error + "</p>";
                return;
            }
            
            let html = "<h3>CGPA: " + data.cgpa + "</h3>";
            
            if (Object.keys(data.results).length === 0) {
                html += "<p>No results found for this student.</p>";
            }
            
            for (let sem in data.results) {
                html += "<h4>" + sem + "</h4><ul>";
                data.results[sem].forEach(r => {
                    html += "<li>" + r.subject + " : " + r.marks + " | GPA: " + r.gpa + "</li>";
                });
                html += "</ul>";
            }
            document.getElementById("output").innerHTML = html;
        })
        .catch(error => {
            console.error("Error:", error);
            document.getElementById("output").innerHTML = "<p style='color:red'>Error loading data</p>";
        });
}

// Student Login - Send OTP
function sendOTP() {
    let email = document.getElementById("email").value;
    
    if (!email) {
        alert("Please enter your email address");
        return;
    }
    
    // Email format validation
    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailPattern.test(email)) {
        alert("Please enter a valid email address");
        return;
    }
    
    fetch("send_otp.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded",
        },
        body: "email=" + encodeURIComponent(email)
    })
    .then(response => response.text())
    .then(data => {
        console.log("OTP Response:", data);
        if (data && data.length === 6 && !isNaN(data)) {
            alert("OTP sent! Check console for OTP: " + data);
            document.getElementById("otp_section").style.display = "block";
        } else {
            alert("Error: " + data);
        }
    })
    .catch(error => {
        console.error("Error:", error);
        alert("Failed to send OTP. Please try again.");
    });
}

// Student Login - Verify OTP
function verifyOTP() {
    let email = document.getElementById("email").value;
    let otp = document.getElementById("otp").value;
    
    if (!email || !otp) {
        alert("Please enter both email and OTP");
        return;
    }
    
    fetch("verify_otp.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded",
        },
        body: "email=" + encodeURIComponent(email) + "&otp=" + encodeURIComponent(otp)
    })
    .then(response => response.text())
    .then(data => {
        if (data === "success") {
            alert("Login successful!");
            window.location.href = "dashboard.html";
        } else {
            alert("Login failed: " + data);
        }
    })
    .catch(error => {
        console.error("Error:", error);
        alert("Failed to verify OTP. Please try again.");
    });
}

// Admin Login
function adminLogin() {
    let email = document.getElementById("admin_email").value;
    let password = document.getElementById("admin_pass").value;
    
    if (!email || !password) {
        alert("Please enter both email and password");
        return;
    }
    
    fetch("admin_login.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded",
        },
        body: "email=" + encodeURIComponent(email) + "&password=" + encodeURIComponent(password)
    })
    .then(response => response.text())
    .then(data => {
        if (data === "success") {
            alert("Admin login successful!");
            window.location.href = "admin_dashboard.html";
        } else {
            alert("Invalid credentials: " + data);
        }
    })
    .catch(error => {
        console.error("Error:", error);
        alert("Login failed. Please try again.");
    });
}

// Dashboard - Load Results
function loadData() {
    let id = document.getElementById("student_id").value;
    let semester = document.getElementById("semester_select") ? document.getElementById("semester_select").value : "";
    
    if (!id) {
        document.getElementById("output").innerHTML = "<p style='color:red'>Please enter a Student ID</p>";
        return;
    }
    
    let url = "get_results.php?id=" + encodeURIComponent(id);
    if (semester && semester !== "") {
        url += "&semester=" + encodeURIComponent(semester);
    }
    
    fetch(url)
        .then(res => res.json())
        .then(data => {
            if (data.error) {
                document.getElementById("output").innerHTML = "<p style='color:red'>" + data.error + "</p>";
                return;
            }
            
            let html = "<h3>CGPA: " + data.cgpa + "</h3>";
            
            if (Object.keys(data.results).length === 0) {
                html += "<p>No results found for this student.</p>";
            }
            
            for (let sem in data.results) {
                html += "<h4>" + sem + "</h4><ul>";
                data.results[sem].forEach(r => {
                    html += "<li>" + r.subject + " : " + r.marks + " | GPA: " + r.gpa + "</li>";
                });
                html += "</ul>";
            }
            document.getElementById("output").innerHTML = html;
        })
        .catch(error => {
            console.error("Error:", error);
            document.getElementById("output").innerHTML = "<p style='color:red'>Error loading data</p>";
        });
}

// Load Student Info (for admin panel)
function loadStudentInfo() {
    let id = document.getElementById("student_id").value;
    
    if (!id) {
        alert("Please enter Student ID");
        return;
    }
    
    fetch("get_student.php?id=" + encodeURIComponent(id))
        .then(res => res.json())
        .then(data => {
            if (data.error) {
                alert(data.error);
            } else {
                document.getElementById("student_name").value = data.name || "";
                document.getElementById("student_email").value = data.email || "";
                document.getElementById("student_dept").value = data.department || "";
            }
        })
        .catch(error => {
            console.error("Error:", error);
            alert("Error loading student info");
        });
}

// Add Student
function addStudent() {
    let id = document.getElementById("student_id").value;
    let name = document.getElementById("student_name").value;
    let email = document.getElementById("student_email").value;
    let department = document.getElementById("student_dept").value;
    
    if (!id || !name || !email) {
        alert("Please fill all required fields");
        return;
    }
    
    fetch("add_student.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded",
        },
        body: "id=" + encodeURIComponent(id) + 
              "&name=" + encodeURIComponent(name) + 
              "&email=" + encodeURIComponent(email) + 
              "&department=" + encodeURIComponent(department)
    })
    .then(response => response.text())
    .then(data => {
        alert(data);
        if (data === "Added") {
            // Clear form
            document.getElementById("student_id").value = "";
            document.getElementById("student_name").value = "";
            document.getElementById("student_email").value = "";
            document.getElementById("student_dept").value = "";
        }
    })
    .catch(error => {
        console.error("Error:", error);
        alert("Error adding student");
    });
}

// Add Result
function addResult() {
    let student_id = document.getElementById("result_student_id").value;
    let semester = document.getElementById("result_semester").value;
    let subject = document.getElementById("result_subject").value;
    let marks = document.getElementById("result_marks").value;
    let gpa = document.getElementById("result_gpa").value;
    
    if (!student_id || !semester || !subject || !marks) {
        alert("Please fill all required fields");
        return;
    }
    
    fetch("add_result.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded",
        },
        body: "student_id=" + encodeURIComponent(student_id) + 
              "&semester=" + encodeURIComponent(semester) + 
              "&subject=" + encodeURIComponent(subject) + 
              "&marks=" + encodeURIComponent(marks) + 
              "&gpa=" + encodeURIComponent(gpa)
    })
    .then(response => response.text())
    .then(data => {
        alert(data);
        if (data.includes("Successfully")) {
            // Clear form
            document.getElementById("result_student_id").value = "";
            document.getElementById("result_subject").value = "";
            document.getElementById("result_marks").value = "";
            document.getElementById("result_gpa").value = "";
        }
    })
    .catch(error => {
        console.error("Error:", error);
        alert("Error adding result");
    });
}

// Delete Student
function deleteStudent() {
    let id = document.getElementById("delete_student_id").value;
    
    if (!id) {
        alert("Please enter Student ID");
        return;
    }
    
    if (confirm("Are you sure you want to delete this student?")) {
        fetch("delete_student.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded",
            },
            body: "id=" + encodeURIComponent(id)
        })
        .then(response => response.text())
        .then(data => {
            alert(data);
            document.getElementById("delete_student_id").value = "";
        })
        .catch(error => {
            console.error("Error:", error);
            alert("Error deleting student");
        });
    }
}