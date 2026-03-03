import {Pipe, PipeTransform} from '@angular/core';

@Pipe({
  name: 'currency'
})
export class CurrencyPipe implements PipeTransform {
  transform(value: any, currency: string = 'KM'): string {
    value = parseFloat(value);
    return value.toFixed(2) + ' ' + currency;
  }
}
