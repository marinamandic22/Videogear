import {Injectable} from "@angular/core";
import {environment} from "../../../environments/environment";
import {HttpClient} from "@angular/common/http";
import {Observable} from "rxjs";
import {IProduct} from "../product";
import {PaginatedResponse} from "../../shared/models/paginated-response";

@Injectable({
  providedIn: 'root'
})

export class ProductService {
  baseUrl: string = environment.apiUrl + '/products'

  constructor(private http: HttpClient) {
  }

  getProducts(params: any = {}): Observable<PaginatedResponse<IProduct>> {
    return this.http.get<PaginatedResponse<IProduct>>(this.baseUrl, {params: params});
  }

  searchProducts(query: string, params: any = {}): Observable<PaginatedResponse<IProduct>> {
    return this.http.get<PaginatedResponse<IProduct>>(this.baseUrl, {
      params: { ...params, q: query }
    });
  }

  getProduct(slug: string): Observable<IProduct> {
    return this.http.get<IProduct>(`${this.baseUrl}/${slug}`);
  }
}
