import { Component, ElementRef, ViewChild, AfterViewInit, OnDestroy, NgZone, HostListener } from '@angular/core';

@Component({
  selector: 'app-background',
  standalone: true,
  template: '<canvas #bgCanvas></canvas>',
  styles: [`
    canvas {
      position: fixed;
      top: 0;
      left: 0;
      width: 100vw;
      height: 100vh;
      z-index: -1; /* Ez teszi az üveglapok MÖGÉ a hátteret */
      pointer-events: none; /* Átengedi az egérkattintásokat az űrlapra */
    }
  `]
})
export class BackgroundComponent implements AfterViewInit, OnDestroy {
  @ViewChild('bgCanvas') canvasRef!: ElementRef<HTMLCanvasElement>;
  private ctx!: CanvasRenderingContext2D;
  private particlesArray: any[] = [];
  private animationFrameId!: number;


  private mouse = {
    x: -1000,
    y: -1000,
    radius: 150
  };
  constructor(private ngZone: NgZone) {}

  @HostListener('window:mousemove', ['$event'])
  onMouseMove(event: MouseEvent) {
    this.mouse.x = event.x;
    this.mouse.y = event.y;
  }

  @HostListener('window:mouseout')
  onMouseOut() {
    this.mouse.x = -1000;
    this.mouse.y = -1000;
  }

  @HostListener('window:resize')
  onResize() {
    this.resizeCanvas();
    this.initParticles();
  }

  ngAfterViewInit() {
    this.ctx = this.canvasRef.nativeElement.getContext('2d')!;
    this.resizeCanvas();
    this.initParticles();
    this.ngZone.runOutsideAngular(() => {
      this.animate();
    });
  }

  ngOnDestroy() {
    cancelAnimationFrame(this.animationFrameId);
  }

  private resizeCanvas() {
    this.canvasRef.nativeElement.width = window.innerWidth;
    this.canvasRef.nativeElement.height = window.innerHeight;
  }

  private initParticles() {
    this.particlesArray = [];
    const numberOfParticles = (window.innerWidth * window.innerHeight) / 10000; 
    
    for (let i = 0; i < numberOfParticles; i++) {
      let size = Math.random() * 2 + 1;
      let x = Math.random() * (innerWidth - size * 2) + size;
      let y = Math.random() * (innerHeight - size * 2) + size;
      let directionX = (Math.random() * 1) - 0.5;
      let directionY = (Math.random() * 1) - 0.5;
      this.particlesArray.push({ x, y, directionX, directionY, size });
    }
  }

  private animate = () => {
    this.ctx.clearRect(0, 0, innerWidth, innerHeight);
    for (let i = 0; i < this.particlesArray.length; i++) {
      let p = this.particlesArray[i];
      if (p.x > innerWidth || p.x < 0) p.directionX = -p.directionX;
      if (p.y > innerHeight || p.y < 0) p.directionY = -p.directionY;
      p.x += p.directionX;
      p.y += p.directionY;

      this.ctx.beginPath();
      this.ctx.arc(p.x, p.y, p.size, 0, Math.PI * 2, false);
      this.ctx.fillStyle = 'rgba(255, 255, 255, 0.5)';
      this.ctx.fill();
    }
    this.connectParticles();
    this.animationFrameId = requestAnimationFrame(this.animate);
  }

  private connectParticles() {
    for (let a = 0; a < this.particlesArray.length; a++) {
      let dxMouse = this.mouse.x - this.particlesArray[a].x;
      let dyMouse = this.mouse.y - this.particlesArray[a].y;
      let distanceMouse = Math.sqrt(dxMouse * dxMouse + dyMouse * dyMouse);
      
      if (distanceMouse < this.mouse.radius) {
        this.ctx.beginPath();
        this.ctx.strokeStyle = `rgba(255, 255, 255, ${1 - distanceMouse / this.mouse.radius})`;
        this.ctx.lineWidth = 1;
        this.ctx.moveTo(this.particlesArray[a].x, this.particlesArray[a].y);
        this.ctx.lineTo(this.mouse.x, this.mouse.y);
        this.ctx.stroke();
      }
      for (let b = a; b < this.particlesArray.length; b++) {
        let dx = this.particlesArray[a].x - this.particlesArray[b].x;
        let dy = this.particlesArray[a].y - this.particlesArray[b].y;
        let distance = Math.sqrt(dx * dx + dy * dy);

        if (distance < 100) {
          this.ctx.beginPath();
          this.ctx.strokeStyle = `rgba(255, 255, 255, ${0.2 - distance / 500})`;
          this.ctx.lineWidth = 1;
          this.ctx.moveTo(this.particlesArray[a].x, this.particlesArray[a].y);
          this.ctx.lineTo(this.particlesArray[b].x, this.particlesArray[b].y);
          this.ctx.stroke();
        }
      }
    }
  }
}