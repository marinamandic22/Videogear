import {Component, EventEmitter, Input, OnInit, Output} from '@angular/core';
import {ImageService} from "../image.service";

@Component({
  selector: 'ecm-image-viewer',
  templateUrl: './image-viewer.component.html',
  styleUrls: ['./image-viewer.component.css']
})
export class ImageViewerComponent {
  @Input() imageIds: number[] = [];
  @Output() closeClicked = new EventEmitter<void>();
  currentIndex: number = 0;
  showLoader: boolean = true;

  constructor(private imageService: ImageService) {
  }

  handleImageLoaded(): void {
    this.showLoader = false;
  }

  handleNext(): void {
    if(this.imageIds[this.currentIndex + 1]) {
      this.currentIndex++;
    } else {
      this.currentIndex = 0;
    }
  }

  handlePrev(): void {
    if(this.imageIds[this.currentIndex - 1]) {
      this.currentIndex--;
    } else {
      this.currentIndex = this.imageIds.length - 1;
    }
  }

  handleClose(e): void {
    e.stopPropagation();
    this.closeClicked.emit();
  }

  getImageUrl(): string {
    const imageId = this.imageIds[this.currentIndex];
    return this.imageService.createUrl(imageId, 'w800_h600');
  }
}
