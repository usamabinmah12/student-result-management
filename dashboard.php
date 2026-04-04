<!DOCTYPE html>
<html>
<head>
    <title>Student Dashboard</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }
        .container { max-width: 1000px; margin: auto; background: white; padding: 20px; border-radius: 10px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        input, select, button { margin: 10px 5px; padding: 10px; border: 1px solid #ddd; border-radius: 5px; }
        button { background-color: #4CAF50; color: white; border: none; cursor: pointer; transition: 0.3s; }
        button:hover { background-color: #45a049; }
        .student-info { background: #e3f2fd; padding: 15px; border-radius: 8px; margin: 15px 0; }
        .no-result { background: #fff3cd; border-left: 5px solid #ffc107; padding: 15px; margin: 15px 0; border-radius: 5px; }
        .add-form { background: #f8f9fa; padding: 20px; border-radius: 8px; margin: 15px 0; border: 1px solid #dee2e6; }
        .add-form input, .add-form select { width: calc(100% - 20px); margin: 8px 0; }
        .result-card { background: white; border: 1px solid #ddd; padding: 15px; margin: 10px 0; border-radius: 8px; }
        .semester-card { background: #f8f9fa; border: 1px solid #dee2e6; border-radius: 8px; padding: 15px; margin: 15px 0; }
        .semester-header { background: #007bff; color: white; padding: 10px; margin: -15px -15px 15px -15px; border-radius: 8px 8px 0 0; cursor: pointer; }
        .subject-item { background: white; padding: 10px; margin: 8px 0; border-radius: 5px; display: flex; justify-content: space-between; align-items: center; }
        .cgpa { background: #d4edda; padding: 10px; border-radius: 5px; font-size: 18px; font-weight: bold; margin: 10px 0; }
        .btn-add { background: #007bff; }
        .btn-add:hover { background: #0056b3; }
        .btn-edit { background: #ffc107; color: #333; }
        .btn-edit:hover { background: #e0a800; }
        .btn-delete { background: #dc3545; }
        .btn-delete:hover { background: #c82333; }
        .btn-save { background: #28a745; }
        .btn-cancel { background: #6c757d; }
        h2, h3, h4 { color: #333; }
        hr { margin: 20px 0; }
        .subject-form { background: #e9ecef; padding: 15px; border-radius: 5px; margin-top: 10px; }
        .existing-subjects { max-height: 300px; overflow-y: auto; }
    </style>
</head>
<body>
    <div class="container">
        <h2> Student Result Management System</h2>
        
        <div>
            <input type="text" id="student_id" placeholder="Enter Student ID" style="width: 250px;">
            <button onclick="loadData()">🔍 Load Results</button>
            <button onclick="showAddStudentForm()" style="background: #17a2b8;">Add New Student</button>
        </div>
        
        <div id="output"></div>
    </div>

    <script>
    let currentStudent = null;
    
    function loadData() {
        let id = document.getElementById("student_id").value;
        
        if (!id) {
            document.getElementById("output").innerHTML = "<div class='no-result'>Please enter a Student ID</div>";
            return;
        }
        
        // First check if student exists
        fetch("get_student.php?id=" + encodeURIComponent(id))
            .then(res => res.json())
            .then(student => {
                if (student.error || !student.id) {
                    document.getElementById("output").innerHTML = `
                        <div class="no-result">
                            <strong>Student ID: ${id} not found!</strong>
                            <p>Would you like to register this student?</p>
                            <button onclick="showAddStudentFormWithId('${id}')" class="btn-add">➕ Register Student</button>
                        </div>
                    `;
                    return;
                }
                
                currentStudent = student;
                return fetch("get_results.php?id=" + encodeURIComponent(id)).then(res => res.json()).then(results => ({student, results}));
            })
            .then(data => {
                if (!data) return;
                
                let {student, results} = data;
                
                // Show student info
                let html = `
                    <div class="student-info">
                        <strong> Student:</strong> ${student.name} (${student.id})<br>
                        <strong>Email:</strong> ${student.email}<br>
                        <strong> Department:</strong> ${student.department || 'Not specified'}
                        <br><br>
                        <button onclick="showAddSemesterForm()" class="btn-add"> Add New Semester Results</button>
                    </div>
                `;
                
                // Show CGPA
                html += `<div class="cgpa">Overall CGPA: ${results.cgpa}</div>`;
                
                // Show results by semester
                if (results.results && Object.keys(results.results).length > 0) {
                    for (let sem in results.results) {
                        let semesterGPA = calculateSemesterGPA(results.results[sem]);
                        html += `
                            <div class="semester-card">
                                <div class="semester-header" onclick="toggleSemester('sem_${sem.replace(/\s/g, '_')}')">
                                    <strong>${sem}</strong> - Semester GPA: ${semesterGPA}
                                </div>
                                <div id="sem_${sem.replace(/\s/g, '_')}" class="existing-subjects">
                        `;
                        
                        results.results[sem].forEach(r => {
                            html += `
                                <div class="subject-item">
                                    <span><strong>${r.subject}</strong> : ${r.marks} marks | GPA: ${r.gpa}</span>
                                    <div>
                                        <button onclick="editSubject('${student.id}', '${sem}', '${r.subject}', ${r.marks}, ${r.gpa})" class="btn-edit">✏️ Edit</button>
                                        <button onclick="deleteSubject('${student.id}', '${sem}', '${r.subject}')" class="btn-delete">🗑️ Delete</button>
                                    </div>
                                </div>
                            `;
                        });
                        
                        html += `
                                    <button onclick="showAddSubjectForm('${student.id}', '${sem}')" class="btn-add" style="margin-top: 10px;">➕ Add Subject to ${sem}</button>
                                </div>
                            </div>
                        `;
                    }
                } else {
                    html += `<div class="no-result">No results found! Click "Add New Semester Results" to add.</div>`;
                }
                
                document.getElementById("output").innerHTML = html;
            })
            .catch(error => {
                console.error("Error:", error);
                document.getElementById("output").innerHTML = "<div class='no-result'>❌ Error loading data. Please try again.</div>";
            });
    }
    
    function calculateSemesterGPA(subjects) {
        let totalGPA = 0;
        let count = 0;
        subjects.forEach(s => {
            if (s.gpa) {
                totalGPA += parseFloat(s.gpa);
                count++;
            }
        });
        return count > 0 ? (totalGPA / count).toFixed(2) : 0;
    }
    
    function showAddSemesterForm() {
        let html = `
            <div class="add-form" id="addSemesterForm">
                <h3> Add New Semester</h3>
                <select id="new_semester">
                    <option value="Spring 2025">Spring 2025</option>
                    <option value="Fall 2025">Fall 2025</option>
                    <option value="Spring 2026">Spring 2026</option>
                    <option value="Fall 2026">Fall 2026</option>
                    <option value="Spring 2027">Spring 2027</option>
                </select>
                <br>
                <button onclick="addNewSemester()" class="btn-save">Create Semester</button>
                <button onclick="cancelForm()" class="btn-cancel">Cancel</button>
            </div>
        `;
        
        let outputDiv = document.getElementById("output");
        removeExistingForms();
        outputDiv.insertAdjacentHTML('beforeend', html);
    }
    
    function addNewSemester() {
        let semester = document.getElementById("new_semester").value;
        showAddSubjectForm(currentStudent.id, semester);
        cancelForm();
    }
    
    function showAddSubjectForm(studentId, semester) {
        let html = `
            <div class="add-form" id="addSubjectForm">
                <h3>Add Subject for ${semester}</h3>
                <input type="hidden" id="subject_student_id" value="${studentId}">
                <input type="hidden" id="subject_semester" value="${semester}">
                
                <label>Subject Name:</label>
                <input type="text" id="subject_name" placeholder="e.g., Mathematics, Physics, English">
                
                <label>Marks (0-100):</label>
                <input type="number" id="subject_marks" placeholder="Marks" min="0" max="100">
                
                <label>GPA (0.00 - 4.00):</label>
                <input type="text" id="subject_gpa" placeholder="e.g., 3.50">
                
                <br>
                <button onclick="saveSubject()" class="btn-save">Save Subject</button>
                <button onclick="cancelForm()" class="btn-cancel"> Cancel</button>
            </div>
        `;
        
        let outputDiv = document.getElementById("output");
        removeExistingForms();
        outputDiv.insertAdjacentHTML('beforeend', html);
        
        // Auto-calculate GPA from marks
        let marksInput = document.getElementById("subject_marks");
        if (marksInput) {
            marksInput.addEventListener('input', function() {
                let marks = parseFloat(this.value);
                let gpa = calculateGPA(marks);
                if (!isNaN(gpa)) {
                    document.getElementById("subject_gpa").value = gpa.toFixed(2);
                }
            });
        }
    }
    
    function editSubject(studentId, semester, subject, marks, gpa) {
        let html = `
            <div class="add-form" id="editSubjectForm">
                <h3>✏️ Edit Subject: ${subject}</h3>
                <input type="hidden" id="edit_student_id" value="${studentId}">
                <input type="hidden" id="edit_semester" value="${semester}">
                <input type="hidden" id="edit_subject" value="${subject}">
                
                <label>Marks (0-100):</label>
                <input type="number" id="edit_marks" value="${marks}" placeholder="Marks" min="0" max="100">
                
                <label>GPA (0.00 - 4.00):</label>
                <input type="text" id="edit_gpa" value="${gpa}" placeholder="e.g., 3.50">
                
                <br>
                <button onclick="updateSubject()" class="btn-save">💾 Update Subject</button>
                <button onclick="cancelForm()" class="btn-cancel">❌ Cancel</button>
            </div>
        `;
        
        let outputDiv = document.getElementById("output");
        removeExistingForms();
        outputDiv.insertAdjacentHTML('beforeend', html);
    }
    
    function updateSubject() {
        let student_id = document.getElementById("edit_student_id").value;
        let semester = document.getElementById("edit_semester").value;
        let subject = document.getElementById("edit_subject").value;
        let marks = document.getElementById("edit_marks").value;
        let gpa = document.getElementById("edit_gpa").value;
        
        fetch("add_result.php", {
            method: "POST",
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: `student_id=${encodeURIComponent(student_id)}&semester=${encodeURIComponent(semester)}&subject=${encodeURIComponent(subject)}&marks=${encodeURIComponent(marks)}&gpa=${encodeURIComponent(gpa)}`
        })
        .then(res => res.text())
        .then(data => {
            alert(data);
            cancelForm();
            loadData();
        });
    }
    
    function deleteSubject(studentId, semester, subject) {
        if (confirm(`Are you sure you want to delete ${subject} from ${semester}?`)) {
            fetch("delete_result.php", {
                method: "POST",
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                body: `student_id=${encodeURIComponent(studentId)}&semester=${encodeURIComponent(semester)}&subject=${encodeURIComponent(subject)}`
            })
            .then(res => res.text())
            .then(data => {
                alert(data);
                loadData();
            });
        }
    }
    
    function saveSubject() {
        let student_id = document.getElementById("subject_student_id").value;
        let semester = document.getElementById("subject_semester").value;
        let subject = document.getElementById("subject_name").value;
        let marks = document.getElementById("subject_marks").value;
        let gpa = document.getElementById("subject_gpa").value;
        
        if (!student_id || !semester || !subject || !marks) {
            alert(" Please fill all fields!");
            return;
        }
        
        fetch("add_result.php", {
            method: "POST",
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: `student_id=${encodeURIComponent(student_id)}&semester=${encodeURIComponent(semester)}&subject=${encodeURIComponent(subject)}&marks=${encodeURIComponent(marks)}&gpa=${encodeURIComponent(gpa)}`
        })
        .then(res => res.text())
        .then(data => {
            alert(data);
            cancelForm();
            loadData();
        });
    }
    
    function calculateGPA(marks) {
        if (marks >= 80) return 4.00;
        if (marks >= 75) return 3.75;
        if (marks >= 70) return 3.50;
        if (marks >= 65) return 3.25;
        if (marks >= 60) return 3.00;
        if (marks >= 55) return 2.75;
        if (marks >= 50) return 2.50;
        if (marks >= 45) return 2.25;
        if (marks >= 40) return 2.00;
        return 0.00;
    }
    
    function toggleSemester(id) {
        let element = document.getElementById(id);
        if (element.style.display === "none") {
            element.style.display = "block";
        } else {
            element.style.display = "none";
        }
    }
    
    function showAddStudentForm() {
        let html = `
            <div class="add-form" id="addStudentForm">
                <h3>➕ Register New Student</h3>
                <input type="text" id="new_student_id" placeholder="Student ID">
                <input type="text" id="new_student_name" placeholder="Full Name">
                <input type="email" id="new_student_email" placeholder="Email Address">
                <input type="text" id="new_student_dept" placeholder="Department">
                <br>
                <button onclick="saveNewStudent()" class="btn-save">💾 Save Student</button>
                <button onclick="cancelForm()" class="btn-cancel"> Cancel</button>
            </div>
        `;
        
        let outputDiv = document.getElementById("output");
        removeExistingForms();
        outputDiv.insertAdjacentHTML('afterbegin', html);
    }
    
    function showAddStudentFormWithId(id) {
        let html = `
            <div class="add-form" id="addStudentForm">
                <h3>➕ Register New Student</h3>
                <input type="text" id="new_student_id" value="${id}" placeholder="Student ID" readonly style="background:#e9ecef;">
                <input type="text" id="new_student_name" placeholder="Full Name">
                <input type="email" id="new_student_email" placeholder="Email Address">
                <input type="text" id="new_student_dept" placeholder="Department">
                <br>
                <button onclick="saveNewStudent()" class="btn-save">💾 Save Student</button>
                <button onclick="cancelForm()" class="btn-cancel"> Cancel</button>
            </div>
        `;
        
        let outputDiv = document.getElementById("output");
        removeExistingForms();
        outputDiv.insertAdjacentHTML('afterbegin', html);
    }
    
    function saveNewStudent() {
        let id = document.getElementById("new_student_id").value;
        let name = document.getElementById("new_student_name").value;
        let email = document.getElementById("new_student_email").value;
        let dept = document.getElementById("new_student_dept").value;
        
        if (!id || !name || !email) {
            alert(" Please fill all required fields (ID, Name, Email)");
            return;
        }
        
        fetch("add_student.php", {
            method: "POST",
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: `id=${encodeURIComponent(id)}&name=${encodeURIComponent(name)}&email=${encodeURIComponent(email)}&department=${encodeURIComponent(dept)}`
        })
        .then(res => res.text())
        .then(data => {
            if (data === "Added") {
                alert("✓ Student registered successfully!");
                document.getElementById("student_id").value = id;
                cancelForm();
                loadData();
            } else {
                alert("✗ Error: " + data);
            }
        });
    }
    
    function cancelForm() {
        removeExistingForms();
    }
    
    function removeExistingForms() {
        let forms = ["addStudentForm", "addSemesterForm", "addSubjectForm", "editSubjectForm"];
        forms.forEach(formId => {
            let form = document.getElementById(formId);
            if (form) form.remove();
        });
    }
    </script>
</body>
</html>