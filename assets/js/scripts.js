document.addEventListener('DOMContentLoaded', function() {
    // Add education entry
    document.getElementById('add-education')?.addEventListener('click', function() {
        const educationEntries = document.getElementById('education-entries');
        const newEntry = document.createElement('div');
        newEntry.className = 'education-entry';
        newEntry.innerHTML = `
            <div class="form-group">
                <label>Institution</label>
                <input type="text" name="institution[]" required>
            </div>
            
            <div class="form-group">
                <label>Degree</label>
                <input type="text" name="degree[]" required>
            </div>
            
            <div class="form-group">
                <label>Field of Study</label>
                <input type="text" name="field[]">
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label>Start Date</label>
                    <input type="month" name="start_date[]">
                </div>
                
                <div class="form-group">
                    <label>End Date</label>
                    <input type="month" name="end_date[]">
                </div>
            </div>
            
            <div class="form-group">
                <label>Description</label>
                <textarea name="description[]"></textarea>
            </div>
            
            <button type="button" class="btn danger remove-entry">Remove</button>
        `;
        educationEntries.appendChild(newEntry);
    });

    // Remove entry
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-entry')) {
            const entry = e.target.closest('.education-entry');
            if (entry && document.querySelectorAll('.education-entry').length > 1) {
                entry.remove();
            } else if (entry) {
                // If it's the last entry, just clear the inputs
                entry.querySelectorAll('input, textarea').forEach(input => {
                    input.value = '';
                });
            }
        }
    });

    // Similar functionality for other sections (experience, skills, projects)
    // Would follow the same pattern as education section
});