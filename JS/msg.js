const msg = document.getElementById('msg');
const xMsg = document.getElementById('x-msg');

if (msg) {
    xMsg.addEventListener('click', () => {
        msg.style.display = 'none';
    });
}
