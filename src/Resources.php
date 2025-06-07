<?php

namespace fmarquesto\SapBusinessOneConnector;

enum Resources: string
{
    case BusinessPartners = 'BusinessPartners';
    case SalesOrders = 'SalesOrders';
    case DeliveryNotes = 'DeliveryNotes';
    case Invoices = 'Invoices';
    case PurchaseOrders = 'PurchaseOrders';
    case GoodsReceipts = 'GoodsReceipts';
    case Items = 'Items';
    case ItemGroups = 'ItemGroups';
    case PriceLists = 'PriceLists';
    case TaxCodes = 'TaxCodes';
    case PaymentTerms = 'PaymentTerms';
    case Currencies = 'Currencies';
    case Users = 'Users';
}
