
        (function() {
            // ========== NAVBAR FUNCTIONS ==========
            window.showHomepage = function() {
                alert('🏠 Homepage - Metropolitan University');
                resultInputField();
            };
            
            window.showAuthor = function() {
                alert('✍️ Developed by Usama & Team\nMulti-semester result system with admin panel');
            };
            
            window.showAbout = function() {
                alert('ℹ️ Metropolitan University Result System v3.0\n• Multi-semester support\n• Admin login: usama/usama12\n• Update all semesters at once');
            };
            
            window.showSearch = function() {
                alert('🔍 Search: Enter student ID below');
                resultInputField();
            };
            
            window.showNotifications = function() {
                alert('🔔 No new notifications at this time');
            };

            
            const inputDiv = document.getElementById('stu-id');
            const resultDiv = document.getElementById('resultDisplay');
            const deniedDiv = document.getElementById('deniedMessage');
            const loginCard = document.getElementById('loginCard');
            const adminPanel = document.getElementById('adminPanel');
            
            const studentIdInput = document.getElementById('studentIdInput');
            const submitBtn = document.getElementById('submitResultBtn');
            
            const resultNameSpan = document.getElementById('resultStudentName');
            const resultIdSpan = document.getElementById('resultStudentId');
            
            const subject1Label = document.getElementById('subject1Label');
            const subject2Label = document.getElementById('subject2Label');
            const subject3Label = document.getElementById('subject3Label');
            const subject4Label = document.getElementById('subject4Label');
            
            const grade1 = document.getElementById('grade1');
            const grade2 = document.getElementById('grade2');
            const grade3 = document.getElementById('grade3');
            const grade4 = document.getElementById('grade4');
            
            const semCGPA = document.getElementById('semCGPA');
            const overallCGPA = document.getElementById('overallCGPA');
            const currentSemDisplay = document.getElementById('currentSemDisplay');
            
            const wrongIdSpan = document.getElementById('wrongIdDisplay');
            const openLoginBtn = document.getElementById('openLoginFromDenied');
            
            const adminUsername = document.getElementById('adminUsername');
            const adminPassword = document.getElementById('adminPassword');
            const adminLoginBtn = document.getElementById('adminLoginBtn');
            
            const adminTargetId = document.getElementById('adminTargetId');
            const adminNameInput = document.getElementById('adminNameInput');
            
            // Semester inputs
            const sem1Sub1 = document.getElementById('sem1Sub1');
            const sem1Sub2 = document.getElementById('sem1Sub2');
            const sem1Sub3 = document.getElementById('sem1Sub3');
            const sem1Sub4 = document.getElementById('sem1Sub4');
            const sem1CGPA = document.getElementById('sem1CGPA');
            
            const sem2Sub1 = document.getElementById('sem2Sub1');
            const sem2Sub2 = document.getElementById('sem2Sub2');
            const sem2Sub3 = document.getElementById('sem2Sub3');
            const sem2Sub4 = document.getElementById('sem2Sub4');
            const sem2CGPA = document.getElementById('sem2CGPA');
            
            const sem3Sub1 = document.getElementById('sem3Sub1');
            const sem3Sub2 = document.getElementById('sem3Sub2');
            const sem3Sub3 = document.getElementById('sem3Sub3');
            const sem3Sub4 = document.getElementById('sem3Sub4');
            const sem3CGPA = document.getElementById('sem3CGPA');
            
            const sem4Sub1 = document.getElementById('sem4Sub1');
            const sem4Sub2 = document.getElementById('sem4Sub2');
            const sem4Sub3 = document.getElementById('sem4Sub3');
            const sem4Sub4 = document.getElementById('sem4Sub4');
            const sem4CGPA = document.getElementById('sem4CGPA');
            
            const saveAdminBtn = document.getElementById('saveAdminUpdateBtn');

            const VALID_USERNAME = 'usama';
            const VALID_PASSWORD = 'usama12';

            
            let studentDatabase = {
                '231-115-303': {
                    name: 'Usama Bin Mahbub',
                    overallCGPA: 3.84,
                    semesters: {
                        1: { 
                            sub1: { name: 'Programming Fund.', grade: 'A' },
                            sub2: { name: 'Digital Logic', grade: 'B+' },
                            sub3: { name: 'Calculus', grade: 'A-' },
                            sub4: { name: 'English', grade: 'B' },
                            cgpa: 3.60 
                        },
                        2: { 
                            sub1: { name: 'OOP', grade: 'A-' },
                            sub2: { name: 'Data Structure', grade: 'A' },
                            sub3: { name: 'Discrete Math', grade: 'B+' },
                            sub4: { name: 'Physics', grade: 'A-' },
                            cgpa: 3.70 
                        },
                        3: { 
                            sub1: { name: 'Algorithms', grade: 'A' },
                            sub2: { name: 'DBMS', grade: 'A-' },
                            sub3: { name: 'Math', grade: 'B' },
                            sub4: { name: 'Statistics', grade: 'A' },
                            cgpa: 3.65 
                        },
                        4: { 
                            sub1: { name: 'Operating Sys', grade: 'A' },
                            sub2: { name: 'Computer Net.', grade: 'A-' },
                            sub3: { name: 'Software Eng.', grade: 'B+' },
                            sub4: { name: 'Web Tech', grade: 'A' },
                            cgpa: 3.76 
                        }
                    }
                }
            };

            const semesterSubjects = {
                1: ['Programming Fund.', 'Digital Logic', 'Calculus', 'English'],
                2: ['OOP', 'Data Structure', 'Discrete Math', 'Physics'],
                3: ['Algorithms', 'DBMS', 'Math', 'Statistics'],
                4: ['Operating Sys', 'Computer Net.', 'Software Eng.', 'Web Tech']
            };

            let currentViewSem = 4;
            let pendingUpdateId = '';

            function hideAllCards() {
                inputDiv.classList.add('hidden');
                resultDiv.classList.add('hidden');
                deniedDiv.classList.add('hidden');
                loginCard.classList.add('hidden');
                adminPanel.classList.add('hidden');
            }

            window.resultInputField = function() {
                hideAllCards();
                inputDiv.classList.remove('hidden');
                setTimeout(() => studentIdInput?.focus(), 150);
                if (studentIdInput) studentIdInput.value = '';
                pendingUpdateId = '';
            };

            function displayResultForId(id, semester = 4) {
                const data = studentDatabase[id];
                if (data) {
                    resultNameSpan.innerText = data.name;
                    resultIdSpan.innerText = `ID: ${id}`;
                    
                    const semData = data.semesters[semester] || data.semesters[4];
                    const subjects = semesterSubjects[semester] || semesterSubjects[4];
                    
                    subject1Label.innerText = subjects[0];
                    subject2Label.innerText = subjects[1];
                    subject3Label.innerText = subjects[2];
                    subject4Label.innerText = subjects[3];
                    
                    grade1.innerText = semData.sub1.grade;
                    grade2.innerText = semData.sub2.grade;
                    grade3.innerText = semData.sub3.grade;
                    grade4.innerText = semData.sub4.grade;
                    
                    semCGPA.innerText = semData.cgpa.toFixed(2);
                    overallCGPA.innerText = data.overallCGPA.toFixed(2);
                    currentSemDisplay.innerText = semester;
                    
                    document.querySelectorAll('.sem-btn').forEach(btn => {
                        btn.classList.remove('sem-btn-active', 'bg-amber-500/30');
                        if (parseInt(btn.dataset.sem) === semester) {
                            btn.classList.add('sem-btn-active', 'bg-amber-500/30');
                        }
                    });
                    
                    hideAllCards();
                    resultDiv.classList.remove('hidden');
                }
            }

            function loadAdminPanelForId(id) {
                adminTargetId.innerText = id;
                
                if (studentDatabase[id]) {
                    const data = studentDatabase[id];
                    adminNameInput.value = data.name || '';
                    
                    sem1Sub1.value = data.semesters[1]?.sub1.grade || 'A';
                    sem1Sub2.value = data.semesters[1]?.sub2.grade || 'A';
                    sem1Sub3.value = data.semesters[1]?.sub3.grade || 'B+';
                    sem1Sub4.value = data.semesters[1]?.sub4.grade || 'A';
                    sem1CGPA.value = data.semesters[1]?.cgpa || '3.50';
                    
                    sem2Sub1.value = data.semesters[2]?.sub1.grade || 'A';
                    sem2Sub2.value = data.semesters[2]?.sub2.grade || 'A';
                    sem2Sub3.value = data.semesters[2]?.sub3.grade || 'B+';
                    sem2Sub4.value = data.semesters[2]?.sub4.grade || 'A';
                    sem2CGPA.value = data.semesters[2]?.cgpa || '3.50';
                    
                    sem3Sub1.value = data.semesters[3]?.sub1.grade || 'A';
                    sem3Sub2.value = data.semesters[3]?.sub2.grade || 'A';
                    sem3Sub3.value = data.semesters[3]?.sub3.grade || 'B+';
                    sem3Sub4.value = data.semesters[3]?.sub4.grade || 'A';
                    sem3CGPA.value = data.semesters[3]?.cgpa || '3.50';
                    
                    sem4Sub1.value = data.semesters[4]?.sub1.grade || 'A';
                    sem4Sub2.value = data.semesters[4]?.sub2.grade || 'A';
                    sem4Sub3.value = data.semesters[4]?.sub3.grade || 'B+';
                    sem4Sub4.value = data.semesters[4]?.sub4.grade || 'A';
                    sem4CGPA.value = data.semesters[4]?.cgpa || '3.50';
                } else {
                    adminNameInput.value = '';
                    [sem1Sub1, sem1Sub2, sem1Sub3, sem1Sub4, sem1CGPA,
                     sem2Sub1, sem2Sub2, sem2Sub3, sem2Sub4, sem2CGPA,
                     sem3Sub1, sem3Sub2, sem3Sub3, sem3Sub4, sem3CGPA,
                     sem4Sub1, sem4Sub2, sem4Sub3, sem4Sub4, sem4CGPA].forEach(input => {
                        if (input) input.value = input.id.includes('CGPA') ? '3.50' : 'A';
                    });
                }
                
                hideAllCards();
                adminPanel.classList.remove('hidden');
            }

            // Event Listeners
            document.querySelectorAll('.sem-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    if (!resultDiv.classList.contains('hidden')) {
                        const id = resultIdSpan.innerText.replace('ID: ', '');
                        const sem = parseInt(this.dataset.sem);
                        if (studentDatabase[id]?.semesters[sem]) {
                            displayResultForId(id, sem);
                        } else {
                            alert(`Semester ${sem} data not available.`);
                        }
                    }
                });
            });

            if (submitBtn) {
                submitBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    const rawId = studentIdInput?.value.trim() || '';
                    
                    if (!rawId) {
                        wrongIdSpan.innerText = '(empty)';
                        openLoginBtn?.setAttribute('data-invalid-id', '');
                        pendingUpdateId = '';
                        hideAllCards();
                        deniedDiv.classList.remove('hidden');
                    } else if (studentDatabase[rawId]) {
                        displayResultForId(rawId, 4);
                    } else {
                        wrongIdSpan.innerText = rawId;
                        openLoginBtn?.setAttribute('data-invalid-id', rawId);
                        pendingUpdateId = rawId;
                        hideAllCards();
                        deniedDiv.classList.remove('hidden');
                    }
                });
            }

            if (studentIdInput) {
                studentIdInput.addEventListener('keypress', function(e) {
                    if (e.key === 'Enter') submitBtn.click();
                });
            }

            if (openLoginBtn) {
                openLoginBtn.addEventListener('click', function() {
                    pendingUpdateId = openLoginBtn.getAttribute('data-invalid-id') || '231-000-000';
                    adminUsername.value = '';
                    adminPassword.value = '';
                    hideAllCards();
                    loginCard.classList.remove('hidden');
                });
            }

            if (adminLoginBtn) {
                adminLoginBtn.addEventListener('click', function() {
                    if (adminUsername.value.trim() === VALID_USERNAME && adminPassword.value.trim() === VALID_PASSWORD) {
                        loadAdminPanelForId(pendingUpdateId || '231-000-000');
                    } else {
                        alert('❌ Invalid credentials. Use usama / usama12');
                    }
                });
            }

            if (adminPassword) {
                adminPassword.addEventListener('keypress', function(e) {
                    if (e.key === 'Enter') adminLoginBtn.click();
                });
            }

            if (saveAdminBtn) {
                saveAdminBtn.addEventListener('click', function() {
                    const targetId = adminTargetId.innerText;
                    const newName = adminNameInput.value.trim() || 'Updated Student';
                    
                    if (!studentDatabase[targetId]) studentDatabase[targetId] = { name: newName, semesters: {} };
                    studentDatabase[targetId].name = newName;
                    
                    const semData = [
                        { subs: [sem1Sub1, sem1Sub2, sem1Sub3, sem1Sub4], cgpa: sem1CGPA, sem: 1 },
                        { subs: [sem2Sub1, sem2Sub2, sem2Sub3, sem2Sub4], cgpa: sem2CGPA, sem: 2 },
                        { subs: [sem3Sub1, sem3Sub2, sem3Sub3, sem3Sub4], cgpa: sem3CGPA, sem: 3 },
                        { subs: [sem4Sub1, sem4Sub2, sem4Sub3, sem4Sub4], cgpa: sem4CGPA, sem: 4 }
                    ];
                    
                    semData.forEach(({subs, cgpa, sem}) => {
                        studentDatabase[targetId].semesters[sem] = {
                            sub1: { name: semesterSubjects[sem][0], grade: subs[0].value.trim() || 'A' },
                            sub2: { name: semesterSubjects[sem][1], grade: subs[1].value.trim() || 'A' },
                            sub3: { name: semesterSubjects[sem][2], grade: subs[2].value.trim() || 'B+' },
                            sub4: { name: semesterSubjects[sem][3], grade: subs[3].value.trim() || 'A' },
                            cgpa: parseFloat(cgpa.value.trim()) || 3.50
                        };
                    });
                    
                    let total = 0;
                    for (let i = 1; i <= 4; i++) {
                        total += studentDatabase[targetId].semesters[i].cgpa;
                    }
                    studentDatabase[targetId].overallCGPA = total / 4;
                    
                    displayResultForId(targetId, 4);
                });
            }

            // Initial state
            hideAllCards();
            inputDiv.classList.add('hidden');
        })();
    