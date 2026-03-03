import { Injectable } from '@angular/core';
import { environment } from '../../../environments/environment';

@Injectable({
  providedIn: 'root',
})
export class VatService {
  constructor() {}

  getVatRate(): number {
    return environment.vat.rate;
  }

  getVatLabel(): string {
    return environment.vat.label;
  }

  isPriceIncludingVat(): boolean {
    return environment.vat.included;
  }

  getPriceWithoutVat(priceWithVat: number): number {
    return priceWithVat / (1 + environment.vat.rate);
  }

  getPriceWithVat(priceWithoutVat: number): number {
    return priceWithoutVat * (1 + environment.vat.rate);
  }

  getVatAmount(priceWithVat: number): number {
    const priceWithoutVat = this.getPriceWithoutVat(priceWithVat);
    return priceWithVat - priceWithoutVat;
  }

  getWithoutVatMultiplier(): number {
    return 1 / (1 + environment.vat.rate);
  }
}
