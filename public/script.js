document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('injection-form');
    const endpointSelect = document.getElementById('endpoint');
    const tutorialText = document.getElementById('tutorial-text');
    const sqlQueryDisplay = document.getElementById('sql-query');
    const resultsTable = document.getElementById('results-table');
    const resultsBody = document.getElementById('results-body');
    const errorMessage = document.getElementById('error-message');
    const emptyState = document.getElementById('empty-state');
    const pwdInput = document.getElementById('password');

    const tutorials = {
        'api/getdata.php': "<b>Level 1 (Raw)</b>: The input goes directly into the SQLite query. <br>Goal: Use a <code>UNION SELECT</code> payload to extract the data from the hidden <code>secret_flags</code> table.",
        'api/getdata-encoding.php': "<b>Level 2 (Escaped)</b>: Single quotes are manually escaped. Try to find a way to manipulate the query without needing a single quote.",
        'api/getdata-prepare.php': "<b>Level 3 (Prepared)</b>: This uses <b>PDO Prepared Statements</b>. This is the ultimate defence. Parameters are treated strictly as data. Injection is impossible here.",
        'api/getdata-blind.php': "<b>Level 4 (Blind / Time-Based)</b>: No errors, no data returned. SQLite doesn't have <code>SLEEP()</code>, but it has <code>randomblob()</code>. Try: <code>' AND (SELECT randomblob(1000000000)) -- </code> to force a time delay.",
        'api/getdata-second-order.php': "<b>Level 5 (Second-Order)</b>: Your payload is stored safely, but retrieved and executed unsafely in a second query. Can you extract the flags here?",
        'api/getdata-json.php': "<b>Level 6 (JSON Injection)</b>: The API expects JSON. Break out of the JSON schema: <code>{\"user_id\": \"1' UNION SELECT id, flag_name, flag_value FROM secret_flags --\"}</code>."
    };

    endpointSelect.addEventListener('change', () => {
        tutorialText.innerHTML = tutorials[endpointSelect.value];
        if (endpointSelect.value === 'api/getdata-prepare.php') {
            pwdInput.value = "admin123";
        } else if (endpointSelect.value === 'api/getdata-blind.php') {
            pwdInput.value = "' AND (SELECT randomblob(1000000000)) -- ";
        } else if (endpointSelect.value === 'api/getdata-json.php') {
            pwdInput.value = '{"user_id": "1\' UNION SELECT id, flag_name, flag_value FROM secret_flags --"}';
        } else {
            pwdInput.value = "' UNION SELECT id, flag_name, flag_value FROM secret_flags -- ";
        }
    });

    form.addEventListener('submit', async (e) => {
        e.preventDefault();

        const endpoint = endpointSelect.value;
        const pid = document.getElementById('pid').value;
        const password = document.getElementById('password').value;

        // Reset UI
        emptyState.classList.add('hidden');
        resultsTable.classList.add('hidden');
        errorMessage.classList.add('hidden');
        sqlQueryDisplay.textContent = 'Executing...';

        try {
            // Append format=json so our modified PHP scripts know what to return
            const url = `${endpoint}?PID=${encodeURIComponent(pid)}&Password=${encodeURIComponent(password)}&format=json`;
            const response = await fetch(url);

            // Raw text fallback in case PHP isn't modified properly
            let data;
            const contentType = response.headers.get('content-type');
            if (contentType && contentType.includes('application/json')) {
                data = await response.json();
            } else {
                const text = await response.text();
                throw new Error("Received non-JSON response from server. Make sure the PHP files have been explicitly updated: " + text.substring(0, 100));
            }

            // Show SQL Query
            if (data.sql) {
                // simple syntax highlighter
                let highlightedSql = data.sql
                    .replace(/SELECT|FROM|WHERE|AND|OR|UNION|INSERT|UPDATE|DELETE|SLEEP/g, match => `<span class="sql-highlight">${match}</span>`);
                sqlQueryDisplay.innerHTML = highlightedSql;
            } else {
                sqlQueryDisplay.textContent = '/* Query not available */';
            }

            // Handle Blind SQLi custom message
            if (data.message) {
                sqlQueryDisplay.innerHTML += `<br><br><span style="color: #6ee7b7;">/* SERVER RESPONSE: ${data.message} */</span>`;
            }

            // Show Error
            if (data.error) {
                errorMessage.textContent = data.error;
                errorMessage.classList.remove('hidden');
            }

            // Show Data
            if (data.data && data.data.length > 0) {
                resultsBody.innerHTML = '';
                data.data.forEach(row => {
                    const tr = document.createElement('tr');
                    tr.innerHTML = `
                        <td>${escapeHtml(row.Name || '')}</td>
                        <td>${escapeHtml(row.Salary || '')}</td>
                        <td>${escapeHtml(row.SSN || '')}</td>
                    `;
                    resultsBody.appendChild(tr);
                });
                resultsTable.classList.remove('hidden');
            } else if (!data.error) {
                errorMessage.textContent = 'Query executed successfully, but returned 0 rows.';
                errorMessage.classList.remove('hidden');
                errorMessage.style.color = '#94a3b8';
                errorMessage.style.borderColor = '#334155';
                errorMessage.style.background = 'transparent';
            }

        } catch (error) {
            errorMessage.textContent = 'Error: ' + error.message;
            errorMessage.classList.remove('hidden');
            sqlQueryDisplay.textContent = '/* Network or parsing error */';
        }
    });

    function escapeHtml(unsafe) {
        return (unsafe || '').toString()
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#039;");
    }
});
