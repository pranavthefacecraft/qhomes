import { useLoadScript } from "@react-google-maps/api";
import Map from "./components/Map";

export default function App() {
  const { isLoaded } = useLoadScript({
    googleMapsApiKey: "AIzaSyCjEGPM8XfoV22BVzUmPTRtjoxYcrCTQcI",
    libraries: ["places"],
  });

  if (!isLoaded) return <div>Loading...</div>;
  return <Map />;
}