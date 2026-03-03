import {Component, EventEmitter, Input, Output} from '@angular/core';
import {ICategory} from '../category';

@Component({
  selector: 'ecm-category-card',
  templateUrl: './category-card.component.html',
  styleUrls: ['./category-card.component.css']
})

export class CategoryCardComponent {
  @Input() category: ICategory;
  @Output() productThumbClicked: EventEmitter<any> = new EventEmitter<string>();

  onProductThumbClick(imageIds: number[]): void {
    this.productThumbClicked.emit(imageIds);
  }
}
