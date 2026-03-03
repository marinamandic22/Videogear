import { Injectable } from '@angular/core';
import { environment } from '../../../environments/environment';
import { HttpClient } from '@angular/common/http';
import { BehaviorSubject, Observable, of } from 'rxjs';
import { tap } from 'rxjs/operators';
import { ICategory } from '../category';

@Injectable({
  providedIn: 'root',
})
export class CategoryService {
  baseUrl: string = environment.apiUrl + '/categories';

  // Use BehaviorSubject to share categories across the app
  private categoriesSubject = new BehaviorSubject<ICategory[]>([]);
  public categories$ = this.categoriesSubject.asObservable();

  private loaded = false;

  constructor(private http: HttpClient) {}

  loadCategories(): Observable<ICategory[]> {
    if (this.loaded) {
      return of(this.categoriesSubject.value);
    }

    return this.http.get<ICategory[]>(this.baseUrl).pipe(
      tap({
        next: (categories) => {
          this.categoriesSubject.next(categories);
          this.loaded = true;
        },
        error: (err) => console.error('Error loading categories:', err),
      })
    );
  }

  getCategories(): Observable<ICategory[]> {
    return this.categories$;
  }

  getCurrentCategories(): ICategory[] {
    return this.categoriesSubject.value;
  }
}
