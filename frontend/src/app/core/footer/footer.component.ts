import { Component } from '@angular/core';
import { environment } from '../../../environments/environment';

@Component({
  selector: 'ecm-footer',
  templateUrl: './footer.component.html',
  styleUrls: ['./footer.component.css'],
})
export class FooterComponent {
  currentYear: number = new Date().getFullYear();

  companyName: string = environment.appName;

  socialLinks = [
    {
      name: 'Facebook',
      url: 'https://facebook.com',
      icon: 'fa fa-facebook-f',
    },
    { name: 'Twitter', url: 'https://twitter.com', icon: 'fa fa-twitter' },
    {
      name: 'LinkedIn',
      url: 'https://linkedin.com',
      icon: 'fa fa-linkedin',
    },
    {
      name: 'Instagram',
      url: 'https://instagram.com',
      icon: 'fa fa-instagram',
    },
    { name: 'GitHub', url: 'https://github.com', icon: 'fa fa-github' },
  ];

  footerLinks = {
    company: [
      { labelKey: 'footer.links.about', url: '#' },
      { labelKey: 'footer.links.careers', url: '#' },
      { labelKey: 'footer.links.press', url: '#' },
      { labelKey: 'footer.links.blog', url: '#' },
    ],
    support: [
      { labelKey: 'footer.links.help', url: '#' },
      { labelKey: 'footer.links.contact', url: '#' },
      { labelKey: 'footer.links.faq', url: '#' },
      { labelKey: 'footer.links.terms', url: '#' },
    ],
    legal: [
      { labelKey: 'footer.links.privacy', url: '#' },
      { labelKey: 'footer.links.cookies', url: '#' },
      { labelKey: 'footer.links.disclaimer', url: '#' },
      { labelKey: 'footer.links.sitemap', url: '#' },
    ],
  };
}

