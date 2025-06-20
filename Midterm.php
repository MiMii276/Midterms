
using System;
using System.Collections.Generic;

namespace InventoryManagementSystem
{
    public enum Role
    {
        Admin,
        Staff
    }

    public class RoleEntity
    {
        public int RoleID { get; set; }
        public string RoleName { get; set; }
    }

    public class User
    {
        public int UserID { get; set; }
        public string Username { get; set; }
        public string Password { get; set; }
        public RoleEntity Role { get; set; }
    }

    public class Product
    {
        public int ProductID { get; set; }
        public string Name { get; set; }
        public string Category { get; set; }
        public decimal Price { get; set; }
        public List<Supplier> Suppliers { get; set; } = new List<Supplier>();
    }

    public class Supplier
    {
        public int SupplierID { get; set; }
        public string Name { get; set; }
        public string ContactInfo { get; set; }
    }

    public class SupplierProduct
    {
        public int SupplierID { get; set; }
        public int ProductID { get; set; }
    }

    public class Stock
    {
        public int StockID { get; set; }
        public Product Product { get; set; }
        public Supplier Supplier { get; set; }
        public int QuantityAdded { get; set; }
        public DateTime DateAdded { get; set; }
    }

    public class Sale
    {
        public int SaleID { get; set; }
        public Product Product { get; set; }
        public int QuantitySold { get; set; }
        public DateTime SaleDate { get; set; }
        public decimal TotalAmount { get; set; }
    }

    public class InventoryManager
    {
        private List<Product> products = new List<Product>();
        private List<Supplier> suppliers = new List<Supplier>();
        private List<Stock> stockEntries = new List<Stock>();
        private List<Sale> sales = new List<Sale>();

        public void AddProduct(Product product) => products.Add(product);
        public void AddSupplier(Supplier supplier) => suppliers.Add(supplier);

        public void AddStock(Stock stock)
        {
            stockEntries.Add(stock);
            Console.WriteLine($"Stock added: {stock.Product.Name} x{stock.QuantityAdded}");
        }

        public void RecordSale(Sale sale)
        {
            var stock = stockEntries.Find(s => s.Product.ProductID == sale.Product.ProductID);
            if (stock == null || stock.QuantityAdded < sale.QuantitySold)
            {
                Console.WriteLine("Insufficient stock.");
                return;
            }

            stock.QuantityAdded -= sale.QuantitySold;
            sales.Add(sale);
            Console.WriteLine($"Sale recorded: {sale.Product.Name} x{sale.QuantitySold} for ${sale.TotalAmount}");
        }

        public void GenerateSalesReport()
        {
            Console.WriteLine("Sales Report:");
            foreach (var sale in sales)
            {
                Console.WriteLine($"{sale.SaleDate.ToShortDateString()} - {sale.Product.Name} - Qty: {sale.QuantitySold} - Total: ${sale.TotalAmount}");
            }
        }
    }

    class Program
    {
        static void Main()
        {
            var manager = new InventoryManager();
            var supplier = new Supplier { SupplierID = 1, Name = "Global Foods", ContactInfo = "123-4567" };
            var product = new Product { ProductID = 1, Name = "Chocolate Cake", Category = "Dessert", Price = 15.00M };

            manager.AddSupplier(supplier);
            product.Suppliers.Add(supplier);
            manager.AddProduct(product);

            manager.AddStock(new Stock
            {
                StockID = 1,
                Product = product,
                Supplier = supplier,
                QuantityAdded = 20,
                DateAdded = DateTime.Now
            });

            manager.RecordSale(new Sale
            {
                SaleID = 1,
                Product = product,
                QuantitySold = 2,
                SaleDate = DateTime.Now,
                TotalAmount = 30.00M
            });

            manager.GenerateSalesReport();
