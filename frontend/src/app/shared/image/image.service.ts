import {HttpClient} from '@angular/common/http';
import {Injectable} from '@angular/core';
import {environment} from '../../../environments/environment';

@Injectable({
  providedIn: 'root'
})
export class ImageService {
  constructor(private http: HttpClient) {
  }

  createUrl(imageId: number, spec: string): string {
    if (imageId) {
      return environment.apiUrl + '/images/' + imageId + '/thumb/' + spec;
    }
    return '';
  }
}
