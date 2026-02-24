const canvas = document.getElementById('pipes');
const ctx = canvas.getContext('2d');

function resizeCanvas() {
    canvas.width = window.innerWidth;
    canvas.height = window.innerHeight;
}
resizeCanvas();
window.addEventListener('resize', resizeCanvas);

class Pipe {
    constructor() {

    this.x = Math.random() * canvas.width;
    this.y = Math.random() * canvas.height;
    this.size = 3;
    this.speed = 2 + Math.random() * 2;
    this.angle = Math.random() * Math.PI * 2;
    // this.color = `hsl(${Math.random() * 360}, 80%, 70%)`;
    this.color = `rgb(${Math.floor(Math.random() * 256)},
                        ${Math.floor(Math.random() * 256)},
                        ${Math.floor(Math.random() * 256)})`;
    }

    update() {
    ctx.fillStyle = this.color;
    ctx.fillRect(this.x, this.y, this.size, this.size);

    this.x += Math.cos(this.angle) * this.speed;
    this.y += Math.sin(this.angle) * this.speed;

    // véletlenszerű kanyarodás
        this.angle += (Math.random() - 0.4) * Math.random();

    // ha kimegy a képből, kezdődjön újra máshonnan
    if (
        this.x < -this.size || this.x > canvas.width + this.size ||
        this.y < -this.size || this.y > canvas.height + this.size
    ) {
        this.x = Math.random() * canvas.width;
        this.y = Math.random() * canvas.height;
        this.angle = Math.random() * Math.PI * 2;
    }
    }
}

const pipes = [];
for (let i = 0; i < 450; i++) {
    pipes.push(new Pipe());
}

function animate() {
    ctx.fillStyle = 'rgba(0, 0, 0, 0.1)';
    ctx.fillRect(0, 0, canvas.width, canvas.height);

    pipes.forEach(pipe => pipe.update());

    requestAnimationFrame(animate);
}

animate();
