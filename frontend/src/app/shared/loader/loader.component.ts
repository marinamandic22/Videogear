import { Component, Input, OnChanges, OnDestroy, SimpleChanges } from '@angular/core';

@Component({
  selector: 'ecm-loader',
  templateUrl: './loader.component.html',
  styleUrls: ['./loader.component.css']
})
export class LoaderComponent implements OnChanges, OnDestroy {
  @Input() size: 'small' | 'medium' | 'large' = 'medium';
  @Input() fullScreen: boolean = false;
  @Input() visible: boolean = true;

  isInDom: boolean = true;
  isLeaving: boolean = false;

  private hideTimer: any;

  ngOnChanges(changes: SimpleChanges): void {
    if (changes['visible']) {
      if (this.visible) {
        clearTimeout(this.hideTimer);
        this.isInDom = true;
        this.isLeaving = false;
      } else {
        // Match the loaderFadeOut duration (0.25s)
        this.isLeaving = true;
        this.hideTimer = setTimeout(() => {
          this.isInDom = false;
          this.isLeaving = false;
        }, 280);
      }
    }
  }

  ngOnDestroy(): void {
    clearTimeout(this.hideTimer);
  }
}
