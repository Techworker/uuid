using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using System.IO;
using System.IO.Compression;
using System.Reflection;

namespace ConsoleApplication2
{
    class Program
    {
        static void Main(string[] args)
        {
            // The path wghere the generated files(s) will be put
            string basePath = Path.GetDirectoryName(Assembly.GetExecutingAssembly().Location);

            // The List of available formats for ToString()
            List<string> formats = new List<string>() { "N", "D", "B", "P", "X" };

            // create zip file
            string zipFile = Path.Combine(basePath, "guids.zip");
            if (File.Exists(zipFile))
            {
                File.Delete(zipFile);
            }

            // create zip
            using (FileStream zipStream = new FileStream(zipFile, FileMode.OpenOrCreate))
            {
                // create archive
                using (ZipArchive archive = new ZipArchive(zipStream, ZipArchiveMode.Update))
                {
                    // loop formats and create guids -> push into archive entry
                    formats.ForEach(delegate(string format)
                    {
                        ZipArchiveEntry entry = archive.CreateEntry(format + "_format.txt");
                        Console.WriteLine("Writing GUID format " + format);
                        using (StreamWriter sw = new StreamWriter(entry.Open()))
                        {
                            sw.NewLine = "\n";
                            for (int i = 0; i < 50000; i++)
                            {
                                sw.WriteLine(Guid.NewGuid().ToString(format));
                            }
                        }
                    });
                }
            }

            // thats it..
            Console.WriteLine("Done..");
            Console.ReadLine();
        }
    }
}
