import { BrowserModule } from '@angular/platform-browser';
import { NgModule } from '@angular/core';
import { AppComponent } from './app.component';
import { HeaderComponent } from './core/header/header.component';
import { FooterComponent } from './core/footer/footer.component';
import { BrowserAnimationsModule } from '@angular/platform-browser/animations';
import { CategoryCardComponent } from './category/card/category-card.component';
import { ProductCardComponent } from './product/card/product-card.component';
import { ProductDetailComponent } from './product/detail/product-detail.component';
import { ImageViewerComponent } from './shared/image/viewer/image-viewer.component.spec';
import { RouterModule } from '@angular/router';
import { PageNotFoundComponent } from './core/page-not-found/page-not-found.component';
import { ReactiveFormsModule } from '@angular/forms';
import { ShopComponent } from './shop/shop.component';
import { CartComponent } from './cart/cart.component';
import { CurrencyPipe } from './shared/pipes/currency/currency.pipe';
import { KeyValuePipe } from './shared/pipes/object/key.value.pipe';
import { FilterPipe } from './shared/pipes/filter/filter.pipe';
import { NgxMaskModule, IConfig } from 'ngx-mask';
import { FormsModule } from '@angular/forms';
import { LoaderComponent } from './shared/loader/loader.component';

// import ngx-translate and the http loader
import { TranslateLoader, TranslateModule } from '@ngx-translate/core';
import { TranslateHttpLoader } from '@ngx-translate/http-loader';
import { HttpClient, HttpClientModule } from '@angular/common/http';
import { CheckoutComponent } from './cart/checkout/checkout.component';

@NgModule({
  declarations: [
    AppComponent,
    HeaderComponent,
    FooterComponent,
    ShopComponent,
    CategoryCardComponent,
    ProductCardComponent,
    ProductDetailComponent,
    ImageViewerComponent,
    CartComponent,
    CheckoutComponent,
    PageNotFoundComponent,
    CurrencyPipe,
    KeyValuePipe,
    FilterPipe,
    LoaderComponent,
  ],
  imports: [
    BrowserModule,
    HttpClientModule,
    BrowserAnimationsModule,
    ReactiveFormsModule,
    FormsModule,
    NgxMaskModule.forRoot({
      validation: false,
    }),
    TranslateModule.forRoot({
      loader: {
        provide: TranslateLoader,
        useFactory: HttpLoaderFactory,
        deps: [HttpClient],
      },
    }),
    RouterModule.forRoot([
      { path: '', component: ShopComponent },
      { path: 'search', component: ShopComponent },
      { path: 'product/:slug', component: ProductDetailComponent },
      { path: 'category/:slug', component: ShopComponent },
      { path: 'cart', component: CartComponent },
      { path: 'checkout', component: CheckoutComponent },
      { path: '**', component: PageNotFoundComponent },
    ]),
  ],
  providers: [],
  bootstrap: [AppComponent],
})
export class AppModule {}

// required for AOT compilation
export function HttpLoaderFactory(http: HttpClient): TranslateHttpLoader {
  return new TranslateHttpLoader(http);
}
