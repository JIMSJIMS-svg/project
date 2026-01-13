function addItem() {
            document.getElementById('addItemModal').classList.add('active');
        }

        function closeModal() {
            document.getElementById('addItemModal').classList.remove('active');
        }

        function openEditModal(id, name, quantity, type, issue, condition, room, description, date) {
            document.getElementById('edit-id').value = id;
            document.getElementById('edit-name').value = name;
            document.getElementById('edit-quantity').value = quantity;
            document.getElementById('edit-type').value = type;
            document.getElementById('edit-issue').value = issue;
            document.getElementById('edit-conditions').value = condition;
            document.getElementById('edit-room').value = room;
            document.getElementById('edit-description').value = description;
            document.getElementById('edit-date').value = date;
            document.getElementById('editItemModal').classList.add('active');
        }

        function closeEditModal() {
            document.getElementById('editItemModal').classList.remove('active');
        }

        window.onclick = function(event) {
            const addModal = document.getElementById('addItemModal');
            const editModal = document.getElementById('editItemModal');
            if (event.target == addModal) {
                addModal.classList.remove('active');
            }
            if (event.target == editModal) {
                editModal.classList.remove('active');
            }
        }