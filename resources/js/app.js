require('./bootstrap');
require('./chat');

try {
    document.getElementById('agentSelect').addEventListener('change', function () {
        document.getElementById('agentForm').submit();
    });
    document.getElementById('userSelect').addEventListener('change', function () {
        document.getElementById('userForm').submit();
    });
} catch (error) {
    
}