import {environment} from "../../../environments/environment";
import {Injectable} from "@angular/core";
import {Observable} from "rxjs";
import {HttpClient} from "@angular/common/http";
import {IOrder} from "../order";


@Injectable({
  providedIn: 'root'
})

export class OrderService {
  baseUrl: string = environment.apiUrl + '/orders'

  constructor(private http: HttpClient) {
  }

  createOrder(body: any = {}, params: any = {}): Observable<any> {
    return this.http.post<IOrder>(this.baseUrl, body, params);
  }
}
