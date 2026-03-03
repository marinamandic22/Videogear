import { Component, OnInit } from '@angular/core';
import { CategoryService } from './category/services/category.service';
import { TranslateService } from '@ngx-translate/core';
import { environment } from '../environments/environment';

@Component({
  selector: 'ecm-root',
  templateUrl: './app.component.html',
  styleUrls: ['./app.component.css'],
})
export class AppComponent implements OnInit {
  title = environment.appName;
  loading: boolean = false;
  contentReady: boolean = false;

  constructor(
    private categoryService: CategoryService,
    private translate: TranslateService
  ) {
    const savedLang = localStorage.getItem('app_lang') || environment.lang;
    translate.setDefaultLang(environment.defaultLang);
    translate.use(savedLang);
  }

  ngOnInit(): void {
    this.loading = true;
    this.categoryService.loadCategories().subscribe({
      next: () => {
        this.loading = false;
        setTimeout(() => {
          this.contentReady = true;
        }, 40);
      },
      error: (error) => {
        console.error('Error loading categories:', error);
        this.loading = false;
        setTimeout(() => {
          this.contentReady = true;
        }, 40);
      },
    });
  }
}
