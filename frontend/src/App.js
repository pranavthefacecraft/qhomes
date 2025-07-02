import React from 'react';
import './App.css';

function App() {
  return (
    <div className="App">
      {/* Header */}
      <header style={{ 
        backgroundColor: '#2c3e50', 
        color: 'white', 
        padding: '20px 0',
        boxShadow: '0 2px 10px rgba(0,0,0,0.1)'
      }}>
        <div style={{ 
          maxWidth: '1200px', 
          margin: '0 auto', 
          padding: '0 20px',
          display: 'flex', 
          justifyContent: 'space-between', 
          alignItems: 'center' 
        }}>
          <h1 style={{ margin: 0, fontSize: '28px', fontWeight: 'bold' }}>QHomes</h1>
          <nav>
            <a href="#home" style={{ color: 'white', textDecoration: 'none', margin: '0 15px' }}>Home</a>
            <a href="#properties" style={{ color: 'white', textDecoration: 'none', margin: '0 15px' }}>Properties</a>
            <a href="#about" style={{ color: 'white', textDecoration: 'none', margin: '0 15px' }}>About</a>
            <a href="#contact" style={{ color: 'white', textDecoration: 'none', margin: '0 15px' }}>Contact</a>
          </nav>
        </div>
      </header>

      {/* Hero Section */}
      <section id="home" style={{ 
        background: 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)',
        color: 'white',
        padding: '80px 0',
        textAlign: 'center'
      }}>
        <div style={{ maxWidth: '1200px', margin: '0 auto', padding: '0 20px' }}>
          <h2 style={{ fontSize: '48px', marginBottom: '20px', fontWeight: 'bold' }}>
            Find Your Dream Home
          </h2>
          <p style={{ fontSize: '20px', marginBottom: '40px', opacity: 0.9 }}>
            Discover the perfect property from our extensive collection of homes, apartments, and commercial spaces.
          </p>
          <button style={{
            backgroundColor: '#e74c3c',
            color: 'white',
            border: 'none',
            padding: '15px 30px',
            fontSize: '18px',
            borderRadius: '5px',
            cursor: 'pointer',
            fontWeight: 'bold'
          }}>
            Browse Properties
          </button>
        </div>
      </section>

      {/* Features Section */}
      <section style={{ padding: '80px 0', backgroundColor: '#f8f9fa' }}>
        <div style={{ maxWidth: '1200px', margin: '0 auto', padding: '0 20px' }}>
          <h2 style={{ textAlign: 'center', marginBottom: '50px', fontSize: '36px', color: '#2c3e50' }}>
            Why Choose QHomes?
          </h2>
          <div style={{ 
            display: 'grid', 
            gridTemplateColumns: 'repeat(auto-fit, minmax(300px, 1fr))', 
            gap: '40px' 
          }}>
            <div style={{ textAlign: 'center', padding: '30px', backgroundColor: 'white', borderRadius: '10px', boxShadow: '0 5px 15px rgba(0,0,0,0.1)' }}>
              <div style={{ fontSize: '48px', marginBottom: '20px' }}>🏠</div>
              <h3 style={{ color: '#2c3e50', marginBottom: '15px' }}>Quality Properties</h3>
              <p style={{ color: '#7f8c8d', lineHeight: '1.6' }}>
                Carefully curated selection of high-quality homes and properties to meet your needs.
              </p>
            </div>
            <div style={{ textAlign: 'center', padding: '30px', backgroundColor: 'white', borderRadius: '10px', boxShadow: '0 5px 15px rgba(0,0,0,0.1)' }}>
              <div style={{ fontSize: '48px', marginBottom: '20px' }}>💰</div>
              <h3 style={{ color: '#2c3e50', marginBottom: '15px' }}>Best Prices</h3>
              <p style={{ color: '#7f8c8d', lineHeight: '1.6' }}>
                Competitive pricing and great deals on properties across different price ranges.
              </p>
            </div>
            <div style={{ textAlign: 'center', padding: '30px', backgroundColor: 'white', borderRadius: '10px', boxShadow: '0 5px 15px rgba(0,0,0,0.1)' }}>
              <div style={{ fontSize: '48px', marginBottom: '20px' }}>🤝</div>
              <h3 style={{ color: '#2c3e50', marginBottom: '15px' }}>Expert Support</h3>
              <p style={{ color: '#7f8c8d', lineHeight: '1.6' }}>
                Professional real estate experts to guide you through every step of the process.
              </p>
            </div>
          </div>
        </div>
      </section>

      {/* Properties Section */}
      <section id="properties" style={{ padding: '80px 0' }}>
        <div style={{ maxWidth: '1200px', margin: '0 auto', padding: '0 20px' }}>
          <h2 style={{ textAlign: 'center', marginBottom: '50px', fontSize: '36px', color: '#2c3e50' }}>
            Featured Properties
          </h2>
          <div style={{ 
            display: 'grid', 
            gridTemplateColumns: 'repeat(auto-fit, minmax(350px, 1fr))', 
            gap: '30px' 
          }}>
            {/* Property Card 1 */}
            <div style={{ 
              backgroundColor: 'white', 
              borderRadius: '10px', 
              overflow: 'hidden',
              boxShadow: '0 5px 15px rgba(0,0,0,0.1)',
              transition: 'transform 0.3s ease'
            }}>
              <div style={{ 
                height: '200px', 
                backgroundColor: '#ddd', 
                display: 'flex', 
                alignItems: 'center', 
                justifyContent: 'center',
                fontSize: '18px',
                color: '#666'
              }}>
                Property Image
              </div>
              <div style={{ padding: '25px' }}>
                <h3 style={{ color: '#2c3e50', marginBottom: '10px' }}>Modern Villa</h3>
                <p style={{ color: '#7f8c8d', marginBottom: '15px' }}>Beautiful 4-bedroom villa with garden</p>
                <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center' }}>
                  <span style={{ fontSize: '24px', fontWeight: 'bold', color: '#e74c3c' }}>$450,000</span>
                  <button style={{
                    backgroundColor: '#3498db',
                    color: 'white',
                    border: 'none',
                    padding: '8px 16px',
                    borderRadius: '5px',
                    cursor: 'pointer'
                  }}>
                    View Details
                  </button>
                </div>
              </div>
            </div>

            {/* Property Card 2 */}
            <div style={{ 
              backgroundColor: 'white', 
              borderRadius: '10px', 
              overflow: 'hidden',
              boxShadow: '0 5px 15px rgba(0,0,0,0.1)',
              transition: 'transform 0.3s ease'
            }}>
              <div style={{ 
                height: '200px', 
                backgroundColor: '#ddd', 
                display: 'flex', 
                alignItems: 'center', 
                justifyContent: 'center',
                fontSize: '18px',
                color: '#666'
              }}>
                Property Image
              </div>
              <div style={{ padding: '25px' }}>
                <h3 style={{ color: '#2c3e50', marginBottom: '10px' }}>City Apartment</h3>
                <p style={{ color: '#7f8c8d', marginBottom: '15px' }}>Luxury 2-bedroom apartment downtown</p>
                <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center' }}>
                  <span style={{ fontSize: '24px', fontWeight: 'bold', color: '#e74c3c' }}>$280,000</span>
                  <button style={{
                    backgroundColor: '#3498db',
                    color: 'white',
                    border: 'none',
                    padding: '8px 16px',
                    borderRadius: '5px',
                    cursor: 'pointer'
                  }}>
                    View Details
                  </button>
                </div>
              </div>
            </div>

            {/* Property Card 3 */}
            <div style={{ 
              backgroundColor: 'white', 
              borderRadius: '10px', 
              overflow: 'hidden',
              boxShadow: '0 5px 15px rgba(0,0,0,0.1)',
              transition: 'transform 0.3s ease'
            }}>
              <div style={{ 
                height: '200px', 
                backgroundColor: '#ddd', 
                display: 'flex', 
                alignItems: 'center', 
                justifyContent: 'center',
                fontSize: '18px',
                color: '#666'
              }}>
                Property Image
              </div>
              <div style={{ padding: '25px' }}>
                <h3 style={{ color: '#2c3e50', marginBottom: '10px' }}>Family House</h3>
                <p style={{ color: '#7f8c8d', marginBottom: '15px' }}>Spacious 3-bedroom house with backyard</p>
                <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center' }}>
                  <span style={{ fontSize: '24px', fontWeight: 'bold', color: '#e74c3c' }}>$320,000</span>
                  <button style={{
                    backgroundColor: '#3498db',
                    color: 'white',
                    border: 'none',
                    padding: '8px 16px',
                    borderRadius: '5px',
                    cursor: 'pointer'
                  }}>
                    View Details
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>

      {/* Footer */}
      <footer style={{ backgroundColor: '#2c3e50', color: 'white', padding: '40px 0', textAlign: 'center' }}>
        <div style={{ maxWidth: '1200px', margin: '0 auto', padding: '0 20px' }}>
          <h3 style={{ marginBottom: '20px' }}>QHomes</h3>
          <p style={{ marginBottom: '20px', opacity: 0.8 }}>
            Your trusted partner in finding the perfect home
          </p>
          <div style={{ marginBottom: '20px' }}>
            <button style={{ color: 'white', background: 'none', border: 'none', textDecoration: 'underline', margin: '0 15px', cursor: 'pointer' }}>Privacy Policy</button>
            <button style={{ color: 'white', background: 'none', border: 'none', textDecoration: 'underline', margin: '0 15px', cursor: 'pointer' }}>Terms of Service</button>
            <button style={{ color: 'white', background: 'none', border: 'none', textDecoration: 'underline', margin: '0 15px', cursor: 'pointer' }}>Contact Us</button>
          </div>
          <p style={{ opacity: 0.6, fontSize: '14px' }}>
            © 2025 QHomes. All rights reserved.
          </p>
        </div>
      </footer>
    </div>
  );
}

export default App;
